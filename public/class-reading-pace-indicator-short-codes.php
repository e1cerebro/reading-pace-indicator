<?php

class Reading_Pace_Indicator_Short_Codes {
	/**
	 * The name of this plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Constructor function that sets the plugin name and version, and calls the `load_hooks` method.
	 *
	 * @param string $plugin_name
	 * @param string $plugin_version
	 */
	public function __construct($plugin_name, $plugin_version) {
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->load_hooks();
	}

	/**
	 * Adds action hooks
	 *
	 * @return void
	 */
	private function load_hooks() {
		add_shortcode('show_read_pace_indicator', array( $this, 'show_read_pace_indicator_callback'));
	}

	public function show_read_pace_indicator_callback($attr) {
		if(isset($attr['post']) && !empty($attr['post'])) {
			$the_post = get_post($attr['post']);
			$util = new Utils();
			$duration = $util->generateReadDuration($the_post->post_content);
			echo $duration;
		}
	}
}