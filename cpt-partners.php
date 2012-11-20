<?php
/*
Plugin Name: Custom Post Type Partners
Plugin URL: https://github.com/Horttcore/Custom-Post-Type-Partners
Description:
Version: 0.1
Author: Ralf Hortt
Author URL: http://horttcore.de/
*/



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
class Custom_Post_Type_Partner
{



	/**
	 * Constructor
	 *
	 **/
	function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		load_plugin_textdomain( 'cpt-partners', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}



	/**
	 * undocumented function
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{
		add_meta_box( 'partner-meta', __( 'Information', 'cpt-partners' ), array( $this, 'meta_box' ), 'partner' );
	}



	/**
	 * Partner meta box
	 *
	 * @access public
	 * @param obj $post Post object
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function meta_box( $post )
	{
		$meta = get_post_meta( $post->ID, '_meta', TRUE );
		wp_nonce_field( 'save-partner-meta', 'partner-meta-nounce' );
		?>
		<table class="form-table">
			<tr>
				<th><label for="partner-url"><?php _e( 'URL', 'cpt-partners' ); ?></label></th>
				<td><input type="text" name="partner-url" id="partner-url" value="<?php echo $meta['url'] ?>"></td>
			</tr>
		</table>
		<?php
	}


	/**
	 * Post updated messages
	 *
	 * @param array $messages Update Messages
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function post_updated_messages( $messages )
	{
		global $post, $post_ID;

		$messages['partner'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Partner updated. <a href="%s">View partner</a>', 'cpt-partners'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.', 'cpt-partners'),
			3 => __('Custom field deleted.', 'cpt-partners'),
			4 => __('Partner updated.', 'cpt-partners'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Partner restored to revision from %s', 'cpt-partners'), wp_post_revision_title( (int) $_GET['revision'], FALSE ) ) : FALSE,
			6 => sprintf( __('Partner published. <a href="%s">View partner</a>', 'cpt-partners'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Partner saved.', 'cpt-partners'),
			8 => sprintf( __('Partner submitted. <a target="_blank" href="%s">Preview partner</a>', 'cpt-partners'), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Partner scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview partner</a>', 'cpt-partners'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Partner draft updated. <a target="_blank" href="%s">Preview partner</a>', 'cpt-partners'), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}



	/**
	 * Register post type
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function register_post_type()
	{
		$labels = array(
			'name' => _x( 'Partners', 'post type general name', 'cpt-partners' ),
			'singular_name' => _x( 'Partner', 'post type singular name', 'cpt-partners' ),
			'add_new' => _x( 'Add New', 'Partner', 'cpt-partners' ),
			'add_new_item' => __( 'Add New Partner', 'cpt-partners' ),
			'edit_item' => __( 'Edit Partner', 'cpt-partners' ),
			'new_item' => __( 'New Partner', 'cpt-partners' ),
			'all_items' => __( 'All Partners', 'cpt-partners' ),
			'view_item' => __( 'View Partner', 'cpt-partners' ),
			'search_items' => __( 'Search Partners', 'cpt-partners' ),
			'not_found' =>  __( 'No Partners found', 'cpt-partners' ),
			'not_found_in_trash' => __( 'No Partners found in Trash', 'cpt-partners' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Partners', 'cpt-partners' )
		);

		$args = array(
			'labels' => $labels,
			'public' => FALSE,
			'publicly_queryable' => FALSE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'query_var' => TRUE,
			'rewrite' => TRUE,
			'capability_type' => 'post',
			'has_archive' => FALSE,
			'hierarchical' => FALSE,
			'menu_position' => NULL,
			'supports' => array( 'title', 'thumbnail', 'page-attributes' )
		);

		register_post_type( 'partner', $args );
	}



	/**
	 * Callback to save the partner meta data
	 *
	 * @access public
	 * @param int $post_id Post ID
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function save_post( $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !wp_verify_nonce( $_POST['partner-meta-nounce'], 'save-partner-meta' ) )
			return;

		update_post_meta( $post_id, '_meta', array(
			'url' => $_POST['partner-url']
		) );
	}

}
new Custom_Post_Type_Partner;