<?php

namespace MPL\Publisher;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc as Align;

class WordPublisher implements IPublisher {

    private $word;

    private $tempPath;

    private $config;

    private $coverFile;

    private $is_first_chapter = true;

    private $title;

    private $author_name;

    private $section_style = array(
        'paperSize' => 'Letter',
        'Orientation' => 'portrait'
    );

    public function __construct()
    {
        $this->word = new PhpWord();
        $this->config = $this->word->getDocInfo();

        $this->config->setCompany('MPL-Publisher by Ferran Figueredo, https://wordpress.mpl-publisher.com/');

        $this->word->addTitleStyle(1, array('size' => 20, 'bold' => true), array('align' => Align::CENTER));
        $this->word->getSettings()->setUpdateFields(true);
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

        $this->title = $title;

        $this->config->setTitle($title);
    }

    public function setSubtitle($subtitle)
    {
        if ($subtitle == '') return;

        $this->setTitle($this->title . ': ' . $subtitle);
    }

    public function setAuthor($author_name)
    {
        if (trim($author_name) == '') return $author_name;

        $this->author_name = $author_name;

        $this->config->setCreator($author_name);
    }

    public function setPublisher($publisher_name)
    {
        $this->config->setCompany($publisher_name);
    }

    public function setCoverImage($filename, $image_data)
    {
        $this->coverFile = $this->tempPath . '/' . $filename;

        file_put_contents($this->coverFile, $image_data);

        $cover = $this->word->addSection($this->section_style);

        $cover->addImage($this->coverFile, array(
            'width' => 460,
            'alignment' => Align::CENTER
        ));

        $cover->addPageBreak();
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
        $this->config->setCreated(strtotime($date));
    }

    public function setRights($rights_text)
    {
        //
    }
    
    public function addChapter($id, $title, $content, $image = null)
    {
        if ($this->is_first_chapter) $this->initSections();

        $this->section->addTitle($title, 1);
        $this->section->addTextBreak(4);

        if ( ! is_null($image)) $this->section->addImage($image);

        Html::addHtml($this->section, $content);

        $this->section->addPageBreak();
    }

    public function send($filename)
    {
        $filepath = $this->tempPath . '/' . $filename . '.docx';

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

    private function initSections()
    {
        $p_style = array('align' => Align::CENTER);
        $h1_style = array('size' => '24', 'bold' => true);
        $h2_style = array('size' => '20', 'bold' => true);

        if ($this->title and $this->author_name)
        {
            $text_cover = $this->word->addSection($this->section_style);

            $text_cover->addText($this->author_name, $h2_style, $p_style);
            $text_cover->addTextBreak(2);
            $text_cover->addText($this->title, $h1_style, $p_style);
            $text_cover->addPageBreak();
        }

        $toc_section = $this->word->addSection($this->section_style);

        $toc_section->addText('Contents', $h2_style, $p_style);
        $toc_section->addTextBreak(2);
        $toc_section->addTOC(array('size' => 12), array(), 1, 1);

        $this->section = $this->word->addSection(array_merge($this->section_style, array(
            'pageNumberingStart' => '1'
        )));

        $header = $this->section->addHeader();
        $header->addText($this->title, array(), $p_style);

        $footer = $this->section->addFooter();
        $footer->addPreserveText('{PAGE}', array(), $p_style);

        $this->is_first_chapter = false;
    }

}