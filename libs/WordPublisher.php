<?php

namespace MPL\Publisher;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;

class WordPublisher implements IPublisher {

    private $word;

    private $tempPath;

    private $config;

    private $bookTitle;

    private $coverFile;

    public function __construct()
    {
        $this->word   = new PhpWord();
        $this->config = $this->word->getDocInfo();

        $this->config->setCompany('MPL-Publisher by Ferran Figueredo, https://mpl-publisher.ferranfigueredo.com/');
    }

    public function setTmpPath($tempPath)
    {
        $this->tempPath = $tempPath;

        Settings::setTempDir($this->tempPath);
    }

    public function setIdentifier($id)
    {
        //
    }

    public function setTitle($title)
    {
        if (trim($title) == '') return;

        $this->bookTitle = $title;

        $this->config->setTitle($title);
    }

    public function setAuthor($author_name)
    {
        if (trim($author_name) == '') return $author_name;

        $this->config->setCreator($author_name);
    }

    public function setPublisher($publisher_name)
    {
        $this->config->setLastModifiedBy($publisher_name);
    }

    public function setCoverImage($filename, $image_data)
    {
        $this->coverFile = $this->tempPath . '/' . $filename;

        $image = file_put_contents($this->coverFile, $image_data);
        $cover = $this->word->addSection();

        $cover->addImage($this->coverFile);
    }

    public function setTheme($theme, $custom_css)
    {
        //
    }

    public function setDescription($description)
    {
        if (trim($description) == '') return;

        $this->config->setDescription($description);
    }

    public function setLanguage($language)
    {
        //
    }

    public function setDate($date)
    {
        $this->config->setCreated($date);
    }

    public function setRights($rights_text)
    {
        //
    }
    
    public function addChapter($id, $title, $content)
    {
        $section = $this->word->addSection();

        $section->addTitle($title, 1);
        Html::addHtml($section, $content);

        $this->addHeader($section);
    }

    public function send($filename)
    {
        $filepath = $this->tempPath . '/' . $filename . '.docx';

        $toc = $this->word->addSection();
        $toc->addTOC();

        $writer = IOFactory::createWriter($this->word, 'Word2007');
        $writer->save($filepath);

        // http://phpword.readthedocs.org/en/latest/recipes.html#download-the-produced-file-automatically
        // https://github.com/PHPOffice/PHPWord/issues/449
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename . '.docx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        readfile($filepath);

        unlink($this->coverFile);
        unlink($filepath);
    }

    private function addHeader($section)
    {
        if ($this->bookTitle)
        {
            $header = $section->addHeader();
            $header->addText($this->bookTitle);
        }
    }

}