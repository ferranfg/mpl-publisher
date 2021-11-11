<?php

namespace MPL\Publisher;

class JsonPublisher extends PremiumPublisher implements IPublisher {

    public function addChapter($id, $title, $content)
    {
        if ( ! array_key_exists('chapters', $this->params)) $this->params['chapters'] = array();

        array_push($this->params['chapters'], array(
            'title' => $title,
            'content' => $content
        ));
    }

    public function send($filename)
    {
        if ( ! mpl_is_premium()) $this->throwPremiumAlert();

        $filepath = $this->tempPath . '/' . $filename . '.json';

        file_put_contents($filepath, json_encode($this->params));

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename . '.json');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');

        readfile($filepath);

        unlink($filepath);
    }
}