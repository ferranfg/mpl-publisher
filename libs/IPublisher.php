<?php namespace MPL\Publisher;

interface IPublisher {

	public function setIdentifier($id);

	public function setTitle($title);

	public function setAuthor($authorName);

	public function setPublisher($publisherName);

	public function setCoverImage($fileName, $imageData);

	public function setCustomCSS($content);

	public function setDescription($description);

	public function setLanguage($language);

	public function setDate($date);

	public function setRights($rightsText);
	
	public function addChapter($id, $title, $content);

	public function send($filename);

}