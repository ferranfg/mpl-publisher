<?php

namespace MPL\Publisher;

class NovelPublisher extends PremiumPublisher implements IPublisher {

    public function send($filename)
    {
        return $this->request('novel', $filename);
    }
}