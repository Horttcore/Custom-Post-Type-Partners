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
final class Custom_Post_Type_Partner_Admin
{



	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function __construct()
	{

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

	} // END __construct



	/**
	 * Meta box
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function add_meta_boxes()
	{

		add_meta_box( 'partner-meta', __( 'Information', 'custom-post-type-partners' ), array( $this, 'meta_box' ), 'partner' );

	} // END add_meta_boxes



	/**
	 * Partner meta box
	 *
	 * @access public
	 * @param obj $post Post object
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function meta_box( $post )
	{

		$meta = get_post_meta( $post->ID, '_meta', TRUE );
		wp_nonce_field( 'save-partner-meta', 'partner-meta-nounce' );

		?>

		<table class="form-table">
			<tr>
				<th><label for="partner-url"><?php _e( 'URL', 'custom-post-type-partners' ); ?></label></th>
				<td><input type="text" name="partner-url" id="partner-url" value="<?php if ( isset( $meta['url'] ) ) echo esc_url_raw( $meta['url'] ) ?>"></td>
			</tr>
		</table>

		<?php

	} // END meta_box



	/**
	 * Post updated messages
	 *
	 * @access public
	 * @param array $messages Update Messages
	 * @return array Update Messages
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function post_updated_messages( $messages )
	{

		$post             = get_post();
		$post_type        = 'partner';
		$post_type_object = get_post_type_object( $post_type );

		$messages[$post_type] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Partner updated.', 'custom-post-type-partners' ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.' ),
			4  => __( 'Partner updated.', 'custom-post-type-partners' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Partner restored to revision from %s', 'custom-post-type-partners' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Partner published.', 'custom-post-type-partners' ),
			7  => __( 'Partner saved.', 'custom-post-type-partners' ),
			8  => __( 'Partner submitted.', 'custom-post-type-partners' ),
			9  => sprintf( __( 'Partner scheduled for: <strong>%1$s</strong>.', 'custom-post-type-partners' ), date_i18n( __( 'M j, Y @ G:i', 'custom-post-type-partners' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Partner draft updated.', 'custom-post-type-partners' )
		);

		if ( !$post_type_object->publicly_queryable )
			return $messages;

		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View partner', 'custom-post-type-partners' ) );
		$messages[$post_type][1] .= $view_link;
		$messages[$post_type][6] .= $view_link;
		$messages[$post_type][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview partner', 'custom-post-type-partners' ) );
		$messages[$post_type][8]  .= $preview_link;
		$messages[$post_type][10] .= $preview_link;

		return $messages;

	} // END post_updated_messages



	/**
	 * Callback to save the partner meta data
	 *
	 * @access public
	 * @param int $post_id Post ID
	 * @return void
	 * @author Ralf Hortt <me@horttcore.de>
	 **/
	public function save_post( $post_id )
	{

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['partner-meta-nounce'] ) || !wp_verify_nonce( $_POST['partner-meta-nounce'], 'save-partner-meta' ) )
			return;

		update_post_meta( $post_id, '_meta', array(
			'url' => esc_url_raw( $_POST['partner-url'] )
		) );

	} // END save_post



} // END final class Custom_Post_Type_Partner_Admin

new Custom_Post_Type_Partner_Admin();
