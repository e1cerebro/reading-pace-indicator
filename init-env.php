<?php
	include_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();