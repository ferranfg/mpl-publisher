<?php

namespace MPL\Publisher;

class FlipbookPublisher extends PremiumPublisher implements IPublisher {

    public function send($filename)
    {
        return $this->request('flipbook', $filename);
    }
}