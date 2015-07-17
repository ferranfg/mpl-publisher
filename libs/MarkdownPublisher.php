<?php

namespace MPL\Publisher;

use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\Yaml\Yaml;

class MarkdownPublisher implements IPublisher {

	private $converter;

	private $zip;

	private $count = 1;

	private $config = array(
		'book' => array(
			'contents' => array(

			)
		)
	);

	public function __construct()
	{
		$this->converter = new HtmlConverter();

		$this->zip = new \PHPZip\Zip\Stream\ZipStream('ZipStreamExample1.zip');

		$this->zip->addDirectory("Contents");
		$this->zip->addDirectory("Resources");
	}

	public function setIdentifier($id)
	{
		$this->config['book']['isbn'] = $id;
	}

	public function setTitle($title)
	{
		$this->config['book']['title'] = $title;
	}

	public function setAuthor($authorName)
	{
		$this->config['book']['author'] = $authorName;
	}

	public function setPublisher($publisherName)
	{
		$this->config['book']['publisher'] = $publisherName;
	}

	public function setCoverImage($fileName, $imageData)
	{

	}

	public function setDescription($description)
	{

	}

	public function setLanguage($language)
	{

	}

	public function setDate($date)
	{

	}

	public function setRights($rightsText)
	{

	}
	
	public function addChapter($id, $title, $content)
	{
		$markdown = $this->converter->convert('<h1 class="chapter-title">' . $title . '</h1>' . $content);

		$this->zip->addFile($markdown, "Contents/{$this->count}-{$title}.md");

		$this->config['book']['contents'][] = array(
			"element" => "chapter",
			"number"  => $this->count,
			"content" => "{$this->count}-{$title}.md"
		);

		$this->count++;
	}

	public function save($filename, $dir)
	{

	}

	public function send($filename)
	{
		$this->zip->addFile(Yaml::dump($this->config), "config.yml");

		$this->zip->closeStream();
		$this->zip->finalize();
	}
}