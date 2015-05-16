<?php

namespace MPL\Publisher;

interface IPublisher {

	public function setIdentifier($id);

	public function setTitle($title);

	public function setAuthor($authorName);

	public function setPublisher($publisherName);
	
	public function addChapter($title, $content);

	public function save($filename, $dir);

}