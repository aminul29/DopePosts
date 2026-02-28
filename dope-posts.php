<?php
/**
 * Plugin Name: Dope Posts
 * Description: Elementor widget for a masonry posts grid with search, filters, and AJAX load more.
 * Version: 1.1.0
 * Author: Aminul Islam
 * Text Domain: dope-posts
 * Requires Plugins: elementor
 *
 * Elementor tested up to: 3.25.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DOPE_POSTS_VERSION', '1.1.0' );
define( 'DOPE_POSTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'DOPE_POSTS_URL', plugin_dir_url( __FILE__ ) );

require_once DOPE_POSTS_PATH . 'includes/class-dope-posts-plugin.php';

Dope_Posts_Plugin::instance();
