<?php
/**
 * Plugin Name: Reading Pace Indicator
 * Plugin URI: #
 * Description: This plugin adds the time to read on your blog single page
 * Version: 1.0.0
 * Author: Christian Nwachukwu
 * Author URI: #
 * Text Domain: reading-pace-indicator
 */


/**
 * This block of code checks if the WPINC constant is not defined. 
 * If it isn't defined, it stops the execution of the script by using the `die` statement.
 * This is to prevent unauthorized access to this script, as it's a best practice to prevent outside access to WordPress core files.
 */
if (!defined('WPINC')) {
    die;
}

/**
 * This line includes the `class-reading-duration.php` file, which contains the main code for the plugin.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-reading-pace-indicator.php';

/**
 * This line creates an instance of the `Reading_Pace_Indicator` class, which will start the plugin.
 */
new Reading_Pace_Indicator();