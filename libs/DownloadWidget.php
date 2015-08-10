<?php namespace MPL\Publisher;

use WP_Widget;

class DownloadWidget extends WP_Widget {

	private $base;

	public function __construct()
	{
		$this->base = new PublisherBase();

		if (isset($_POST['download_ebook']) and wp_verify_nonce($_POST['_wpnonce'], 'download_ebook'))
		{
			$this->base->generateBook(true);
		}

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

	public function widget($args, $instance, $return = false)
	{
		$this->base->data['args'] = $args;
		$this->base->data['instance'] = $instance;

		$this->base->data['wp_nonce_field'] = wp_nonce_field('download_ebook', '_wpnonce', true, false);

		$widget = $this->base->view('widget.php', $this->base->data);

		if ($return) return $widget;

		echo $widget;
	}

	public function shortcode($attr, $content)
	{
		$args = array(
	        'before_widget' => '<div class="widget_mpl_publisher shortcode_mpl_publisher">',
	        'after_widget'  => '</div>',
	        'before_title'  => '<h4>',
	        'after_title'   => '</h4>'
	    );

	    $instance = array_merge(array(
	        'title'     => $content,
	        'download'  => false,
	        'external'  => false
	    ), $attr ? $attr : array());

	    return $this->widget($args, $instance, true);
	}
}