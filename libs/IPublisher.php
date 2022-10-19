<?php

namespace MPL\Publisher;

interface IPublisher {

    public function setIdentifier($id);

    public function setTitle($title);

    public function setSubtitle($subtitle);

    public function setAuthor($author_name);

    public function setPublisher($publisher_name);

    public function setCoverImage($filename, $image_data);

    public function setTheme($theme, $content_css);

    public function setDescription($description);

    public function setLanguage($language);

    public function setDate($date);

    public function setRights($rights_text);

    public function addChapter($id, $title, $content, $image = null);

    public function addFile($id, $name, $data, $mime_type);

    public function send($filename);

}