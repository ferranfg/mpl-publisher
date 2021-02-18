<?php

namespace MPL\Publisher;

use MOBI;
use MOBIFile;

class MobiPublisher implements IPublisher {

    private $mobi;

    private $content;

    public function __construct()
    {
        $this->mobi = new MOBI();
        $this->content = new MOBIFile();
    }

    public function setIdentifier($id)
    {
        $this->content->set('uniqueID', $id);
    }

    public function setTitle($title)
    {
        $this->content->set('title', $title);
    }

    public function setAuthor($authorName)
    {
        $this->content->set('author', $authorName);
    }

    public function setPublisher($publisherName)
    {
        $this->content->set('publisher', $publisherName);
    }

    public function setCoverImage($fileName, $imageData)
    {
        $this->content->set('cover', $imageData);
    }

    public function setTheme($theme, $customCSS)
    {
        //
    }

    public function setDescription($description)
    {
        $this->content->set('description', $description);
    }

    public function setLanguage($language)
    {
        $this->content->set('language', $language);
    }

    public function setDate($date)
    {
        $this->content->set('date', $date);
    }

    public function setRights($rightsText)
    {
        $this->content->set('copyright', $rightsText);
    }
    
    public function addChapter($id, $title, $content)
    {
        $this->content->appendChapterTitle($title);
        $this->content->appendChapter($content);
        $this->content->appendPageBreak();
    }

    public function send($filename)
    {
        $this->mobi->setContentProvider($this->content);
        $this->mobi->download($filename . '.mobi');
    }

}