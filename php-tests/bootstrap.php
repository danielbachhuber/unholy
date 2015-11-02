<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

require_once $_tests_dir . '/includes/functions.php';

function _register_theme() {
	add_filter( 'pre_option_template', function(){
		return 'twentyfifteen';
	});
	add_filter( 'pre_option_stylesheet', function(){
		return 'twentyfifteen';
	});
}
tests_add_filter( 'muplugins_loaded', '_register_theme' );

require $_tests_dir . '/includes/bootstrap.php';

require dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php';
