<?php

namespace MPL\Publisher;

class Book {

	public function __construct($attrs)
	{
		foreach ($attrs as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getImageSrc()
	{
		$coverSrc = wp_get_attachment_image_src($this->cover, 'full');
		
		if (count($coverSrc) == 4 and isset($coverSrc[0])) $coverSrc = $coverSrc[0];

		return $coverSrc;
	}
}