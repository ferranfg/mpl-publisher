<?php

namespace MPL\Publisher;

use League\HTMLToMarkdown\HtmlConverter;
use Symfony\Component\Yaml\Yaml;
use PHPZip\Zip\File\Zip;

class RemotePublisher implements IPublisher {

	private $converter;
	private $zip;
	private $ext = '.zip';
	private $edition = false;
	private $num = 1;

	private $config = array(
		'book' => array(
			'generator' => array("name" => "mpl-publisher"),
			'contents' => array(),
			'editions' => array(
				'ebook' => array(
					'format' => 'epub'
				),
				'kindle' => array(
					'extends' => 'epub',
					'format'  => 'mobi'
				),
				'print' => array(
					'format' => 'pdf'
				)
			)
		)
	);

	public function __construct($format = 'markd')
	{
		$this->converter = new HtmlConverter();

		$this->zip = new Zip('remote');

		$this->zip->addDirectory("Contents");
		$this->zip->addDirectory("Resources/Templates");

		switch ($format)
		{
			case 'kndle':
				$this->edition = 'kindle';
				$this->ext 	   = '.mobi';
				break;
			case 'pdfpr':
				$this->edition = 'print';
				$this->ext     = '.pdf';
				break;
		}
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
		$this->zip->addFile($imageData, "Resources/Templates/{$fileName}");
		
		$this->config['book']['contents'][] = array(
			"element" => "cover",
			"content" => $fileName
		);
	}

	public function setDescription($description)
	{
		if (trim($description) == "") return;

		$this->zip->addFile($description, "Contents/introduction.md");

		$this->config['book']['contents'][] = array(
			"element" => "introduction",
			"content" => "introduction.md"
		);
	}

	public function setLanguage($language)
	{
		$this->config['book']['language'] = $language;
	}

	public function setDate($date)
	{
		$this->config['book']['publication_date'] = $date;
	}

	public function setRights($rightsText)
	{
		if (trim($rightsText) == "") return;

		$this->zip->addFile($rightsText, "Contents/license.md");

		$this->config['book']['contents'][] = array(
			"element" => "license",
			"content" => "license.md"
		);
	}
	
	public function addChapter($id, $title, $content)
	{
		if (trim($content) == "") return;

		if ($this->num == 0) $this->config['book']['contents'][] = array(
			"element" => "toc"
		);

		$markdown = $this->converter->convert($content);
		$chapterTitle = $this->num . '-' . sanitize_title($title) . '.md';

		$this->zip->addFile($markdown, "Contents/" . $chapterTitle);

		$this->config['book']['contents'][] = array(
			"element" => "chapter",
			"number"  => $this->num,
			"content" => $chapterTitle,
			"title"	  => $title
		);

		$this->num++;
	}

	public function send($filename)
	{
		$this->zip->addFile(Yaml::dump($this->config), "config.yml");

		$response = $this->zip;

		if ($this->edition)
		{
			$remote = wp_remote_post(MPL_API_URL . 'services/convert', array(
				'timeout' 	 => 15,
				'user-agent' => 'MPL-Publisher/' . MPL_VERSION . '; ' . home_url(),
				'body' 		 => array(
					'edition' => $this->edition,
					'file'    => $this->zip->getZipData()
				)
			));

			$response = new Zip('response');
			$response->addFile($remote['body'], $filename . $this->ext);
		}

		return $response->sendZip($filename . ".zip");
	}
}