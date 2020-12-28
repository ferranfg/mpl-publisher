<?php

namespace MPL\Publisher;

class AudiobookPublisher extends PremiumPublisher implements IPublisher
{
    private $params;

    public function setIdentifier($id)
    {
        $this->params['identifier'] = $id;
    }

    public function setTitle($title)
    {
        $this->params['title'] = $title;
    }

    public function setAuthor($authorName)
    {
        $this->params['author'] = $authorName;
    }

    public function setPublisher($publisherName)
    {
        $this->params['publisher'] = $publisherName;
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
        $this->params['description'] = $description;
    }

    public function setLanguage($language)
    {
        $this->params['language'] = $language;
    }

    public function setDate($date)
    {
        $this->params['date'] = $date;
    }

    public function setRights($rightsText)
    {
        $this->params['rights'] = $rightsText;
    }

    public function addChapter($id, $title, $content)
    {
        if ( ! array_key_exists('content', $this->params)) $this->params['content'] = '';

        $content = strip_tags($content);

        $this->params['content'] .= "{$id}. {$title}. {$content}. ";
    }

    public function send($filename)
    {
        return $this->request('audiobook', "{$filename}.mp3", $this->params);
    }

}