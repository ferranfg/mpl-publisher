<?php

namespace MPL\Publisher;

class OnlinePublisher extends PremiumPublisher implements IPublisher {

    public function send($filename)
    {
        return $this->request('online', $filename);
    }
}