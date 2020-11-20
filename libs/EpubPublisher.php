<?php

namespace MPL\Publisher;

use PHPePub\Core\EPub;

class EpubPublisher implements IPublisher {

    private $epub;

    public function setFormat($format = 'epub2')
    {
        $version = $format == 'epub3' ? EPub::BOOK_VERSION_EPUB3 : EPub::BOOK_VERSION_EPUB2;

        $this->epub = new EPub($version);
        $this->epub->setGenerator("MPL-Publisher by Ferran Figueredo, https://mpl-publisher.ferranfigueredo.com/");
    }

    public function setIdentifier($id)
    {
        return $this->epub->setIdentifier($id, EPub::IDENTIFIER_URI);
    }

    public function setTitle($title)
    {
        return $this->epub->setTitle($title);
    }

    public function setAuthor($authorName)
    {
        return $this->epub->setAuthor($authorName, $authorName);
    }

    public function setPublisher($publisherName)
    {
        return $this->epub->setPublisher($publisherName, null);
    }

    public function setCoverImage($fileName, $imageData)
    {
        return $this->epub->setCoverImage($fileName, $imageData);
    }

    public function setTheme($theme, $contentCSS)
    {
        if (trim($contentCSS) == "")
        {
            $contentCSS = file_get_contents($theme['style']);

            foreach ($theme['fonts'] as $name => $path) $this->epub->addFile(
                "{$name}.ttf",
                "{$name}",
                file_get_contents($path),
                "application/x-font-ttf"
            );
        }

        $this->epub->addCSSFile("Style.css", "default", $contentCSS);
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

    public function setRights($rightsText)
    {
        return $this->epub->setRights($rightsText);
    }

    public function addChapter($id, $title, $content)
    {
        $content_start =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
        . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
        . "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
        . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
        . "<head>"
        . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
        . "<title>" . $title . "</title>\n"
        . "<link href=\"../Styles/Style.css\" rel=\"stylesheet\" type=\"text/css\" />\n"
        . "</head>\n"
        . "<body>\n";
        $bookEnd = "</body>\n</html>\n";

        $xmlContent = $content_start . '<h1 class="chapter-title">' . $title . '</h1>' . $content . $bookEnd;

        return $this->epub->addChapter($title, $id . ".html", $xmlContent);
    }

    public function send($filename)
    {
        $this->epub->finalize();
        $this->epub->sendBook($filename);
    }
}