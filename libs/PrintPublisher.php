<?php

namespace MPL\Publisher;

class PrintPublisher extends PremiumPublisher implements IPublisher
{
    public function send($filename)
    {
        return $this->request('print', "{$filename}.pdf");
    }
}