<?php
/**
 * Security, checks if WordPress is running
 **/
if ( !function_exists( 'add_action' ) ) :
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
endif;



/**
*  Plugin
*/
final class Custom_Post_Type_Partner
{



	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 * @since v1.1.0
	 **/
	public function __construct()
	{

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

	} // END __construct



	/**
	 * Load plugin translation
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 * @since v1.1.0
	 **/
	public function load_plugin_textdomain()
	{

		load_plugin_textdomain( 'custom-post-type-partners', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'  );

	} // end load_plugin_textdomain



	/**
	 * Register post type
	 *
	 * @access public
	 * @return void
	 * @since v1.1.0
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function register_post_type()
	{

		register_post_type( 'partner', array(
			'labels' => array(
				'name' => _x( 'Partners', 'post type general name', 'custom-post-type-partners' ),
				'singular_name' => _x( 'Partner', 'post type singular name', 'custom-post-type-partners' ),
				'add_new' => _x( 'Add New', 'Partner', 'custom-post-type-partners' ),
				'add_new_item' => __( 'Add New Partner', 'custom-post-type-partners' ),
				'edit_item' => __( 'Edit Partner', 'custom-post-type-partners' ),
				'new_item' => __( 'New Partner', 'custom-post-type-partners' ),
				'all_items' => __( 'All Partners', 'custom-post-type-partners' ),
				'view_item' => __( 'View Partner', 'custom-post-type-partners' ),
				'search_items' => __( 'Search Partners', 'custom-post-type-partners' ),
				'not_found' =>  __( 'No Partners found', 'custom-post-type-partners' ),
				'not_found_in_trash' => __( 'No Partners found in Trash', 'custom-post-type-partners' ),
				'parent_item_colon' => '',
				'menu_name' => __( 'Partners', 'custom-post-type-partners' )
			),
			'public' => TRUE,
			'publicly_queryable' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'query_var' => TRUE,
			'rewrite' => TRUE,
			'capability_type' => 'post',
			'has_archive' => FALSE,
			'hierarchical' => FALSE,
			'menu_position' => NULL,
			'menu_icon' => 'dashicons-admin-links',
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
		) );

	} // END register_post_type



}

new Custom_Post_Type_Partner;
