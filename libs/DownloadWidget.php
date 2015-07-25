<?php

namespace MPL\Publisher;

use WP_Widget;

class DownloadWidget extends WP_Widget {

	private $base;

	public function __construct()
	{
		$this->base = new PublisherBase();

		if (isset($_POST['mpl-publisher-download'])) $this->base->generateBook(true);

		parent::__construct('mpl_publisher', 'MPL - Download eBook', array(
			'description' => __('A box with information about your eBook', 'publisher')
		));
	}

	public function update($newInstance, $oldInstance)
	{
		$instance = array();

		$instance['title'] 	  = !empty($newInstance['title']) ? strip_tags($newInstance['title']) : '';
		$instance['download'] = !empty($newInstance['download']);
		$instance['external'] = !empty($newInstance['external']);

		return $instance;
	}

	public function form($instance)
	{
		$data = $this->base->data;

		$data['instance'] 	= $instance;

		$data['titleId'] 	  = $this->get_field_id('title');
		$data['titleName'] 	  = $this->get_field_name('title');
		$data['downloadId']   = $this->get_field_id('download');
		$data['downloadName'] = $this->get_field_name('download');
		$data['externalId']   = $this->get_field_id('external');
		$data['externalName'] = $this->get_field_name('external');

		echo $this->base->view('widget-form.php', $data);
	}

	public function widget($args, $instance)
	{
		$this->base->data['args'] = $args;
		$this->base->data['instance'] = $instance;

		echo $this->base->view('widget.php', $this->base->data);
	}
}