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
                'mobi' => array(
                    'extends' => 'ebook',
                    'format' => 'mobi'
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

    public function setAuthor($authorName)
    {
        $this->config['book']['author'] = $authorName;
    }

    public function setPublisher($publisherName)
    {
        $this->config['book']['publisher'] = $publisherName;
    }

    public function setCoverImage($fileName, $imageData)
    {
        $this->zip->addFile($imageData, 'Resources/Templates/' . $fileName);
        
        $this->config['book']['contents'][] = array(
            'element' => 'cover',
            'content' => $fileName
        );
    }

    public function setTheme($theme, $contentCSS)
    {
        if (trim($contentCSS) == '')
        {
            $contentCSS = file_get_contents($theme['style']);

            foreach ($theme['fonts'] as $name => $path)
            {
                $this->zip->addFile(file_get_contents($path), "Resources/Fonts/{$name}.ttf");
            }
        }

        $this->zip->addFile($contentCSS, 'Resources/Templates/Style.css');
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

    public function setRights($rightsText)
    {
        if (trim($rightsText) == '') return;

        $this->zip->addFile($rightsText, 'Contents/license.md');

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