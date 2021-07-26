<?php

namespace MPL\Publisher;

use PHPZip\Zip\File\Zip;
use Html2Text\Html2Text;
use Illuminate\Support\Str;

class PlainPublisher implements IPublisher {

    private $zip;

    private $meta = array();

    public function __construct()
    {
        $this->zip = new Zip('mpl-publisher.zip');
    }

    public function setIdentifier($id)
    {
        $this->meta['Identifier'] = $id;
    }

    public function setTitle($title)
    {
        $this->meta['Title'] = $title;
    }

    public function setAuthor($author_name)
    {
        $this->meta['Author'] = $author_name;
    }

    public function setPublisher($publisher_name)
    {
        $this->meta['Publisher'] = $publisher_name;
    }

    public function setCoverImage($filename, $image_data)
    {
        //
    }

    public function setTheme($theme, $content_css)
    {
        //
    }

    public function setDescription($description)
    {
        $this->meta['Description'] = $description;
    }

    public function setLanguage($language)
    {
        $this->meta['Language'] = $language;
    }

    public function setDate($date)
    {
        $this->meta['Date'] = $date;
    }

    public function setRights($rights)
    {
        $this->meta['Copyrights'] = $rights;
    }

    public function addChapter($id, $title, $content)
    {
        $chapter_id = str_pad($id, 3, '0', STR_PAD_LEFT);
        $chapter_title = $chapter_id . '-' . Str::slug($title) . '.txt';

        $content = new Html2Text($content);

        $this->zip->addFile($content->getText(), $chapter_title);
    }

    public function send($filename)
    {
        $metadata = '';

        foreach ($this->meta as $key => $value) $metadata .= $key . ': ' . $value . "\n";

        $this->zip->addFile($metadata, '000-metadata.txt');

        return $this->zip->sendZip($filename . '.zip');
    }
}