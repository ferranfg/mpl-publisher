<?php

namespace MPL\Publisher;

use PHPZip\Zip\File\Zip;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use League\HTMLToMarkdown\HtmlConverter;

class MarkdownPublisher implements IPublisher {

    private $converter;

    private $zip;

    private $count = 1;

    private $config = array(
        'book' => array(
            'generator' => array('name' => 'mpl-publisher'),
            'contents' => array(),
            'editions' => array(
                'epub' => array(
                    'format' => 'epub'
                ),
                'pdf' => array(
                    'format' => 'pdf'
                )
            )
        )
    );

    public function __construct()
    {
        $this->converter = new HtmlConverter();

        $this->zip = new Zip('mpl-publisher.zip');

        $this->zip->addDirectory('Contents');
        $this->zip->addDirectory('Resources/Templates');
    }

    public function setIdentifier($id)
    {
        $this->config['book']['isbn'] = $id;
    }

    public function setTitle($title)
    {
        $this->config['book']['title'] = $title;
    }

    public function setAuthor($author_name)
    {
        $this->config['book']['author'] = $author_name;
    }

    public function setPublisher($publisher_name)
    {
        $this->config['book']['publisher'] = $publisher_name;
    }

    public function setCoverImage($filename, $image_data)
    {
        $this->zip->addFile($image_data, 'Resources/Templates/' . $filename);
        
        $this->config['book']['contents'][] = array(
            'element' => 'cover',
            'content' => $filename
        );
    }

    public function setTheme($theme, $content_css)
    {
        if (trim($content_css) == '')
        {
            $content_css = file_get_contents($theme['style']);

            foreach ($theme['fonts'] as $name => $path)
            {
                $this->zip->addFile(file_get_contents($path), "Resources/Fonts/{$name}.ttf");
            }
        }

        $this->zip->addFile($content_css, 'Resources/Templates/Style.css');
    }

    public function setDescription($description)
    {
        if (trim($description) == '') return;

        $this->zip->addFile($description, 'Contents/introduction.md');

        $this->config['book']['contents'][] = array(
            'element' => 'introduction',
            'content' => 'introduction.md'
        );
    }

    public function setLanguage($language)
    {
        $this->config['book']['language'] = $language;
    }

    public function setDate($date)
    {
        $this->config['book']['publication_date'] = $date;
    }

    public function setRights($rights_text)
    {
        if (trim($rights_text) == '') return;

        $this->zip->addFile($rights_text, 'Contents/license.md');

        $this->config['book']['contents'][] = array(
            'element' => 'license',
            'content' => 'license.md'
        );
    }
    
    public function addChapter($id, $title, $content)
    {
        if (trim($content) == '') return;

        if ($this->count == 0) $this->config['book']['contents'][] = array(
            'element' => 'toc'
        );

        $markdown = $this->converter->convert($content);
        $chapterTitle = $this->count . '-' . Str::slug($title) . '.md';

        $this->zip->addFile($markdown, 'Contents/' . $chapterTitle);

        $this->config['book']['contents'][] = array(
            'element' => 'chapter',
            'number'  => $this->count,
            'content' => $chapterTitle,
            'title'   => $title
        );

        $this->count++;
    }

    public function send($filename)
    {
        $this->zip->addFile(Yaml::dump($this->config), 'config.yml');

        return $this->zip->sendZip($filename . '.zip');
    }
}