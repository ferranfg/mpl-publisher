<?php

namespace MPL\Publisher;

use WP_Widget;

class DownloadWidget extends WP_Widget {

	private $base;

	public function __construct()
	{
		parent::__construct(
			'mpl_download_widget',
			'MPL - Download eBook',
			array('description' => __('A box with information about your eBook', 'publisher'))
		);

		$this->base = new PublisherBase();
	}

	public function form($instance)
	{
		echo $this->base->view('widget-form.php', $this->base->data);
	}

	public function widget($args, $instance)
	{
		echo $this->base->view('widget.php', $this->base->data);
	}

}