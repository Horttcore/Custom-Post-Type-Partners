<?php
/**
 * Plugin Name: Custom Post Type Partners
 * Plugin URI: http://horttcore.de
 * Description: Manage partners
 * Version: 1.1.0
 * Author: Ralf Hortt
 * Author URI: http://horttcore.de
 * Text Domain: custom-post-type-partners
 * Domain Path: /languages/
 * License: GPL2
 */


/**
 * Security, checks if WordPress is running
 **/
if ( !function_exists( 'add_action' ) ) :
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
endif;

require( 'classes/custom-post-type-partners.php' );

if ( is_admin() )
	require( 'classes/custom-post-type-partners.admin.php' );
