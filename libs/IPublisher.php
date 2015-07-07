<?php

namespace MPL\Publisher;

interface IPublisher {

	public function setIdentifier($id);

	public function setTitle($title);

	public function setAuthor($authorName);

	public function setPublisher($publisherName);

	public function setCoverImage($fileName, $imageData, $mimetype);

	public function setDescription($description);

	public function setLanguage($language);

	public function setDate($date);

	public function setRights($rightsText);
	
	public function addChapter($id, $title, $content);

	public function save($filename, $dir);

	public function send($filename);

}