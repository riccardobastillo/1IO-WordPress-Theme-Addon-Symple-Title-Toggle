<?php     
	/*
	Plugin Name: Simply Toggle Title 
	Description: Add-on to toggle titles on posts and pages for Simply Theme
	Version: 0.0.2
	Author: Jose Mortellaro
	Author URI: https://josemortellaro.com/
	Text Domain: stt
	Domain Path: /languages
	License: GPLv2 or later
	*/
if( !defined('ABSPATH') ) die();
add_action( 'after_setup_theme', 'ric_stt_after_setup_theme' );
//It include the PHP functions for the customize previw
function ric_stt_after_setup_theme(){
	load_plugin_textdomain('stt', FALSE, dirname( plugin_basename(__FILE__) ).'/languages/');
	if( is_customize_preview() ){
		require untrailingslashit( dirname( __FILE__ ) ).'/admin/stt-admin-customize.php';
	}
	if( is_admin() ){
		require untrailingslashit( dirname( __FILE__ ) ).'/admin/stt-metabox.php';
	}
}
add_filter( 'the_title', 'ric_stt_disable_titles', 10, 2 ); 
function ric_stt_disable_titles( $title,$id = null ){
	if( !in_the_loop() ) return $title;
	$meta = get_post_meta( $id );
	$flg = false;
	if( !isset( $meta['_ric_stt_title'] ) ){
		if ( is_single() ){
			$flg = get_theme_mod( 'ric_stt_disable_at_single' );
		}
		elseif( is_front_page() && is_page() ){
			$flg = get_theme_mod( 'ric_stt_disable_at_front_home' );
		}
		elseif( is_page() ){
			$flg = get_theme_mod( 'ric_stt_disable_at_page' );
		}
	}
	else{
		$values = $meta['_ric_stt_title'];
		$flg = !$values[0];
	}
    return $flg ? '' : $title;	
}