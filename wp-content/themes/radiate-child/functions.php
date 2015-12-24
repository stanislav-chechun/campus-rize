<?php

	function add_scripts() {
		wp_enqueue_style( 'bootstrap.min', get_home_url() . '/wp-content/themes/radiate-child/bootstrap/bootstrap.min.css' );
		wp_enqueue_style( 'lato', '//fonts.googleapis.com/css?family=Lato:400,300,300italic,700,400italic,700italic&subset=latin,latin-ext' );
		wp_enqueue_style( 'montserrat', '//fonts.googleapis.com/css?family=Montserrat:400,700&subset=latin,latin-ext' );
		wp_enqueue_script( 'bootstrap.min.js', get_home_url() . '/wp-content/themes/radiate-child/bootstrap/bootstrap.min.js', array(), false, true );
		wp_enqueue_script( 'scripts.js', get_home_url() . '/wp-content/themes/radiate-child/js/scripts.js', array(), false, true );
		//wp_enqueue_script( 'mobile.js', get_template_directory_uri() . '/js/mobile.js', array(), false, true );
	}
	add_action( 'wp_enqueue_scripts', 'add_scripts' );

?>