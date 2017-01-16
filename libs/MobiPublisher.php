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

    }

    public function setCoverImage($fileName, $imageData)
    {

    }

    public function setCustomCSS($content)
    {

    }

    public function setDescription($description)
    {

    }

    public function setLanguage($language)
    {

    }

    public function setDate($date)
    {

    }

    public function setRights($rightsText)
    {

    }
    
    public function addChapter($id, $title, $content)
    {
        $this->content->appendChapterTitle($title);
        
    }

    public function send($filename)
    {
        $this->mobi->setContentProvider($this->content);
        $this->mobi->download($filename . '.mobi');
    }

}