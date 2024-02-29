<?php

namespace MPL\Publisher;

class AudiobookPublisher extends PremiumPublisher implements IPublisher
{
    private $voice_name = '';

    public function addChapter($id, $title, $content, $image = null)
    {
        if ( ! array_key_exists('content', $this->params)) $this->params['content'] = '';

        $content = strip_tags($content);

        $this->params['content'] .= "{$id}. {$title}. {$content}. ";
    }

    public function send($filename)
    {
        return $this->request('audiobook', "{$filename}.mp3");
    }

    public function setVoiceName($voice_name)
    {
        $this->voice_name = $voice_name;
    }

    public function setLanguage($language)
    {
        $this->params['language'] = $this->voice_name == '' ? $language : $this->voice_name;
    }
}