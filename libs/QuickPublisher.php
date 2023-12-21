<?php

namespace MPL\Publisher;

class QuickPublisher extends PremiumPublisher implements IPublisher
{
    public function send($filename)
    {
        return $this->request('quick', "{$filename}.zip");
    }
}