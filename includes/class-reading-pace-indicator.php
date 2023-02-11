<?php
/**
 * Reading Pace Indicator plugin main class.
 *
 * The purpose of this class is to load the required files,
 * initialize the plugin name and version, and instantiate
 * the public and admin classes for the plugin.
 *
 */
class Reading_Pace_Indicator {
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
	 * Class constructor.
	 *
	 * Load the required files, initialize the plugin name and version,
	 * and instantiate the public class for the plugin.
	 *
	 */
	public function __construct() {
		$this->load_required_files();
		$this->set_plugin_name_and_version();
		$this->run();
	}

	/**
	 * Load the required files for the plugin.
	 *
	 * @access private
	 */
	private function load_required_files() {
		include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'utils/constants.php' );
		include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'utils/utility-class.php' );
		include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-reading-pace-indicator-public.php' );
		include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-reading-pace-indicator-admin.php' );
	}

	/**
	 * Set the plugin name and version.
	 *
	 * @access private
	 */
	private function set_plugin_name_and_version() {
		$this->plugin_name = 'read-pace-duration';
		$this->plugin_version = defined( 'RPI_PLUGIN_VERSION' ) ? RPI_PLUGIN_VERSION : '1.0.0';
	}

	/**
	 * Run the public class for the plugin.
	 *
	 * @access private
	 */
	private function run() {
		new Reading_Pace_Indicator_Public( $this->plugin_name, $this->plugin_version );
		new Reading_Pace_Indicator_Admin( $this->plugin_name, $this->plugin_version );
	}
}
