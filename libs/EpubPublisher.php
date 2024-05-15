<?php

namespace MPL\Publisher;

use PHPePub\Core\EPub;
use PHPePub\Helpers\StringHelper;

class EpubPublisher implements IPublisher {

    private $epub;

    private $format;

    public function setFormat($format = 'epub2', $language = 'en', $direction = 'ltr')
    {
        $this->format = $format == 'epub3' ? EPub::BOOK_VERSION_EPUB3 : EPub::BOOK_VERSION_EPUB2;

        $this->epub = new EPub($this->format, $language, $direction);
        $this->epub->setGenerator("MPL-Publisher by Ferran Figueredo, https://wordpress.mpl-publisher.com/");
    }

    public function setIdentifier($id)
    {
        if ($id == "") $id = (string) StringHelper::createUUID();

        return $this->epub->setIdentifier($id, EPub::IDENTIFIER_UUID);
    }

    public function setTitle($title)
    {
        return $this->epub->setTitle($title);
    }

    public function setSubtitle($subtitle)
    {
        if ($subtitle == '') return;

        return $this->setTitle($this->epub->getTitle() . ': ' . $subtitle);
    }

    public function setAuthor($author_name)
    {
        return $this->epub->setAuthor($author_name, $author_name);
    }

    public function setPublisher($publisher_name)
    {
        return $this->epub->setPublisher($publisher_name, null);
    }

    public function setCoverImage($filename, $image_data)
    {
        return $this->epub->setCoverImage($filename, $image_data);
    }

    public function setTheme($theme, $content_css)
    {
        if (trim($content_css) == "")
        {
            $content_css = file_get_contents($theme['style']);

            foreach ($theme['fonts'] as $name => $path) $this->epub->addFile(
                "{$name}.ttf",
                "{$name}",
                file_get_contents($path),
                "font/ttf"
            );
        }

        $this->epub->addCSSFile("Style.css", "default", $content_css);
    }

    public function setDescription($description)
    {
        return $this->epub->setDescription($description);
    }

    public function setLanguage($language)
    {
        return $this->epub->setLanguage($language);
    }

    public function setDate($date)
    {
        return $this->epub->setDate(strtotime($date));
    }

    public function setRights($rights_text)
    {
        return $this->epub->setRights($rights_text);
    }

    public function addChapter($id, $title, $content, $image = null)
    {
        $doctype = $this->format == EPub::BOOK_VERSION_EPUB3 ?
            "<!DOCTYPE html>\n" :
            "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";

        $content_start = $doctype
        . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
        . "<head>"
        . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
        . "<title>" . $title . "</title>\n"
        . "<link href=\"./Style.css\" rel=\"stylesheet\" type=\"text/css\" />\n"
        . "</head>\n"
        . "<body>\n";
        $content_end = "</body>\n</html>\n";

        $html_content = implode('', array(
            $content_start,
            $image ? "<img src=\"{$image}\" alt=\"{$title}\" />" : '',
            "<h1 class=\"chapter-title\">{$title}</h1>",
            $content,
            $content_end
        ));

        return $this->epub->addChapter($title, $id . ".xhtml", $html_content);
    }

    public function addFile($id, $name, $data, $mime_type)
    {
        return $this->epub->addFile($name, $id, $data, $mime_type);
    }

    public function send($filename)
    {
        $this->epub->setisReferencesAddedToToc(false);
        $this->epub->finalize();
        $this->epub->sendBook($filename);
    }
}