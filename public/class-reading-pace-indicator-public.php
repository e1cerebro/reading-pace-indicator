<?php

class Reading_Pace_Indicator_Public {
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
		if(esc_attr(get_option('hide_reading_pace_indicator_option_name')) != 1) {
			add_filter('the_content', array( $this, 'prepend_read_duration' ), 0);
		}
		add_action('wp_enqueue_scripts', array( $this, 'enqueue_files' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'add_custom_theme_colors' ));
	}

	/**
	 * Enqueues two CSS files:
	 *
	 * @return void
	 */
	public function enqueue_files() {
		$sanitized_name = sanitize_title_with_dashes($this->plugin_name);
		wp_enqueue_style(
			$sanitized_name . '-font-awesome',
			plugin_dir_url(dirname(__FILE__)) . 'assets/fontawesome-5.15.4/css/all.min.css',
			[],
			'5.15.4',
			'all'
		);
		wp_enqueue_style(
			$sanitized_name . '-app-css',
			plugin_dir_url(dirname(__FILE__)) . 'dist/app.css',
			[],
			$this->plugin_version,
			'all'
		);
	}

	public function add_custom_theme_colors() {
		$text_color = esc_attr(get_option('text_color_option_name', '#000'));

		$css = <<<CSS
		:root {
			--textColor: {$text_color};
		}
		CSS;

		wp_register_style( 'dma-inline-style', false );
		wp_enqueue_style( 'dma-inline-style' );
		wp_add_inline_style( 'dma-inline-style', $css );
	}

	/**
	 * This function prepends the read duration of a single post to the post's content.
	 * The read duration is calculated using the `generateReadDuration` method from the `Utils` class.
	 * If the current page is not a single post, the original content is returned without modification.
	 *
	 * @param string $content
	 * @return string
	 */
	public function prepend_read_duration($content) {
		if (is_single()) {
			$util = new Utils();
			$duration = $util->generateReadDuration($content);
			$html = "<div class='rd-read-duration-wrapper'>
				<div class='the-timer-content'>
					{$duration} read time
				</div>
			</div>";
			return $html . $content;
		}
		return $content;
	}
}