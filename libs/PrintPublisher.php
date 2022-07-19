<?php

namespace MPL\Publisher;

class PrintPublisher extends PremiumPublisher implements IPublisher
{
    public function addChapter($id, $title, $content, $image = null)
    {
        if ( ! array_key_exists('chapters', $this->params)) $this->params['chapters'] = array();

        array_push($this->params['chapters'], array(
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'image' => $image,
        ));
    }

    public function send($filename)
    {
        return $this->request('print', "{$filename}.pdf");
    }
}