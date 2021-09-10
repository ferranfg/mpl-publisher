<?php

namespace MPL\Publisher;

use MOBI;
use MOBIFile;
use Exception;

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

    public function setAuthor($author_name)
    {
        $this->content->set('author', $author_name);
    }

    public function setPublisher($publisher_name)
    {
        $this->content->set('publisher', $publisher_name);
    }

    public function setCoverImage($filename, $image_data)
    {
        $this->content->set('cover', $image_data);
    }

    public function setTheme($theme, $custom_css)
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

    public function setRights($rights_text)
    {
        $this->content->set('copyright', $rights_text);
    }
    
    public function addChapter($id, $title, $content)
    {
        $this->content->appendChapterTitle($title);
        $this->content->appendChapter($content);
        $this->content->appendPageBreak();
    }

    public function send($filename)
    {
        if ( ! mpl_is_premium())
        {
            $msg = [
                __('This is a premium feature and it is not available on the free version.', 'publisher'),
                __('Please, visit our homepage and get access to this and more features.', 'publisher')
            ];

            throw new Exception("⚠️ " . implode(" ", $msg));
        }

        $this->mobi->setContentProvider($this->content);
        $this->mobi->download($filename . '.mobi');
    }

}