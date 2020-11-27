<?php

namespace MPL\Publisher;

class PrintPublisher extends PremiumPublisher implements IPublisher
{
    private $title;

    private $language;

    private $chapters = [];

    public function setIdentifier($id)
    {
        //
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setAuthor($authorName)
    {
        //
    }

    public function setPublisher($publisherName)
    {
        //
    }

    public function setCoverImage($fileName, $imageData)
    {
        //
    }

    public function setTheme($theme, $contentCSS)
    {
        //
    }

    public function setDescription($description)
    {
        //
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setDate($date)
    {
        //
    }

    public function setRights($rightsText)
    {
        //
    }

    public function addChapter($id, $title, $content)
    {
        array_push($this->chapters, ['id' => $id, 'title' => $title, 'content' => $content]);
    }

    public function send($filename)
    {
        return $this->request('print', $filename . '.pdf', [
            'language' => $this->language,
            'title' => $this->title,
            'chapters' => $this->chapters
        ]);
    }
}