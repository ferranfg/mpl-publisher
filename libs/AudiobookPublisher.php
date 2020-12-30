<?php

namespace MPL\Publisher;

class AudiobookPublisher extends PremiumPublisher implements IPublisher
{
    public function addChapter($id, $title, $content)
    {
        if ( ! array_key_exists('content', $this->params)) $this->params['content'] = '';

        $content = strip_tags($content);

        $this->params['content'] .= "{$id}. {$title}. {$content}. ";
    }

    public function send($filename)
    {
        return $this->request('audiobook', "{$filename}.mp3");
    }
}