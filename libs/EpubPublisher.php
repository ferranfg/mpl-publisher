<?php

namespace MPL;

use EPub;

class EpubPublisher implements IPublisher {

	private $epub;

	public function __construct()
	{
		$this->epub = new EPub();
	}

	public function setIdentifier($id)
	{
		$this->epub->setIdentifier($id, EPub::IDENTIFIER_URI);
	}

	public function setTitle($title)
	{
		$this->epub->setTitle($title);
	}

	public function setAuthor($authorName)
	{
		$this->epub->setAuthor($authorName, $authorName);
	}

	public function setPublisher($publisherName)
	{
		$this->epub->setPublisher($publisherName, null);
	}

	public function addChapter($title, $content)
	{
        $content_start =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
        . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
        . "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
        . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
        . "<head>"
        . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
        . "<title>" . $title . "</title>\n"
        . "</head>\n"
        . "<body>\n";
        $bookEnd = "</body>\n</html>\n";
		$xmlContent = $content_start . $content . $bookEnd;

		$this->epub->addChapter($title, $title . ".html", $xmlContent, true, EPub::EXTERNAL_REF_ADD, wp_upload_dir()['basedir']);
	}

	public function save($filename)
	{
		$this->epub->finalize();
		$this->epub->saveBook($filename, wp_upload_dir()['basedir']);
	}
}