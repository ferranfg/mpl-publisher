<?php

namespace MPL\Publisher;

use PHPePub\Core\EPub;

class EpubPublisher implements IPublisher {

	private $epub;

	private $basePath;

	public function __construct($format, $basePath)
	{
		$version = $format == 'epub3' ? EPub::BOOK_VERSION_EPUB3 : EPub::BOOK_VERSION_EPUB2;

		$this->epub = new EPub($version);

		$DS = DIRECTORY_SEPARATOR;
		$assetsPath = $basePath . $DS . 'assets' . $DS;

		$stylePath = $assetsPath . 'css' . $DS;
		$fontsPath = $assetsPath . 'fonts' . $DS;

		$this->epub->addCSSFile("Style.css", "default", file_get_contents($stylePath . 'book.css'));

		$this->epub->addFile("Merriweather-Regular.ttf", "merriweather-regular", file_get_contents($fontsPath . "Merriweather-Regular.ttf"), "application/font-sfnt");
		$this->epub->addFile("Merriweather-Bold.ttf", "merriweather-bold", file_get_contents($fontsPath . "Merriweather-Bold.ttf"), "application/font-sfnt");
		$this->epub->addFile("Merriweather-Italic.ttf", "merriweather-italic", file_get_contents($fontsPath . "Merriweather-Italic.ttf"), "application/font-sfnt");
		$this->epub->addFile("Lato-Bold.ttf", "lato-bold", file_get_contents($fontsPath . "Lato-Bold.ttf"), "application/font-sfnt");

		$this->epub->setGenerator("https://ferranfigueredo.com/mpl-publisher/");
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

	function setCoverImage($fileName, $imageData = null, $mimetype = null)
	{
		return $this->epub->setCoverImage($fileName, $imageData, $mimetype);
	}

	public function addChapter($id, $title, $content)
	{
        $content_start =
        "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
        . "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
        . "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
        . "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
        . "<head>"
        . "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
        . "<title>" . $title . "</title>\n"
        . "<link href=\"../Styles/Style.css\" rel=\"stylesheet\" type=\"text/css\" />\n"
        . "</head>\n"
        . "<body>\n";
        $bookEnd = "</body>\n</html>\n";

		$xmlContent = $content_start . '<h1 class="chapter-title">' . $title . '</h1>' . $content . $bookEnd;

		$this->epub->addChapter($title, $id . ".html", $xmlContent);
	}

	public function save($filename, $dir)
	{
		$this->epub->finalize();
		$this->epub->saveBook($filename, $dir);
	}

	public function send($filename)
	{
		$this->epub->finalize();
		$this->epub->sendBook($filename);
	}
}