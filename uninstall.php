<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       #
 * @since      1.0.0
 *
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if(!empty(get_option('words_per_minute_option_name'))) {
	delete_option('words_per_minute_option_name');
}

if(!empty(get_option('hide_reading_pace_indicator_option_name'))) {
	delete_option('hide_reading_pace_indicator_option_name');
}