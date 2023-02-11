<?php

class Reading_Pace_Indicator_Admin {
	/**
	 * The name of this plugin.
	 * 
	 * @access private
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access private
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
		add_action('admin_menu', [ $this, 'add_admin_menu' ]);
		add_action('admin_init', [ $this, 'add_sections' ]);
		add_action('admin_init', [ $this, 'rad_register_settings' ]);
	}

	/**
	 * Adds the Reading Pace settings page to the WordPress admin menu.
	 */
	public function add_admin_menu() {
		add_menu_page(
			__('Reading Pace Settings', 'reading-pace-indicator'), // Page title
			__('Reading Pace', 'reading-pace-indicator'), // Menu title
			'manage_options', // Capability required to view the page
			'reading_pace_indicator', // Menu slug
			array($this, 'render_admin_page'), // Callback function
			'dashicons-book' // Icon URL
		);
	}

	/**
	 * Adds a section to the Reading Pace Indicator settings page.
	 */
	public function add_sections() {
		add_settings_section(
			'reading_pace_indicator_section',
			__('Reading Pace Indicator Settings', 'reading-pace-indicator'),
			array($this, 'rad_section_render'),
			'reading_pace_indicator',
		);
	}

	public function rad_section_render() {
		//This callback function does not need to have a body
	}

	/**
	 * Register plugin settings.
	 *
	 */
	public function rad_register_settings() {
		register_setting('reading_pace_indicator_option_group', 'words_per_minute_option_name');
		register_setting('reading_pace_indicator_option_group', 'hide_reading_pace_indicator_option_name');

		add_settings_field(
			'words_per_minute_id',
			__('Words Per Minute', 'reading-pace-indicator'), 
			array($this, 'words_per_minute_callback_function'),
			'reading_pace_indicator',
			'reading_pace_indicator_section',
			array( 
				'id' => 'words_per_minute_id', 
				'option_name' => 'words_per_minute_option_name'
			)
		);
		
		add_settings_field(
			'hide_reading_pace_indicator_field',
			__('Hide Reading Pace Indicator', 'reading-pace-indicator'), 
			array($this, 'hide_reading_pace_indicator_callback_function'),
			'reading_pace_indicator',
			'reading_pace_indicator_section',
			array( 
				'id' => 'hide_reading_pace_indicator_id', 
				'option_name' => 'hide_reading_pace_indicator_option_name'
			)
		);
	}

	/**
	 * Callback function for rendering the words per minute field in the settings page.
	 *
	 * @param array $value Array containing the `id` and `option_name` of the field.
	 */
	function words_per_minute_callback_function($value) {
		$id = esc_attr($value['id']);
		$option_name = esc_attr($value['option_name']);
		$option_value = esc_attr(get_option($option_name));
		?>
		<input type="number" name="<?php echo $option_name; ?>" id="<?php echo $id; ?>" value="<?php echo $option_value; ?>" />
		<?php
	}
	
	/**
	 * Callback function for rendering the checkbox for "Hide Reading Pace Indicator" setting.
	 * 
	 * @param array $value Array containing the 'id' and 'option_name' values.
	 */
	function hide_reading_pace_indicator_callback_function( $value ) {
		$id = esc_attr( $value['id'] );
		$option_name = esc_attr( $value['option_name'] );
		$option_value = esc_attr( get_option( $option_name ) );
		
		echo "<input type='checkbox' name='$option_name' id='$id' " . ( $option_value == 1 ? 'checked' : '' ) . " value='1' />";
	}

	/**
	 * Render the admin page for the Reading Pace Indicator plugin.
	 *
	 * This function outputs the HTML for the settings page of the plugin, which allows the user
	 * to adjust the plugin's settings. The page contains a form that is used to submit the settings
	 * to the WordPress database.
	 *
	 * @return void
	 */
	public function render_admin_page() { ?>
		<div class="wrap">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<p><?php _e('You can adjust the settings on this page.', 'reading-pace-indicator'); ?></p>
			<form method='post' action='options.php'>
				<?php
					settings_fields( 'reading_pace_indicator_option_group' );
					do_settings_sections( 'reading_pace_indicator' );
					submit_button();
				?>
			</form>
		</div>
	<?php }
}