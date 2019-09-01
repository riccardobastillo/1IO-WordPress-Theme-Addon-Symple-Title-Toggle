<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
add_action( 'add_meta_boxes', 'ric_stt_add_meta_box' );
/**
 * Adds the meta box to the page screen
 */
function ric_stt_add_meta_box(){
    add_meta_box(
        'ric-stt-meta-box',
        __( 'Title' ),
        'ric_stt_meta_box_cb',
        array( 'post','page' ),
        'side',
        'low'
    );
}
function ric_stt_meta_box_cb(){
	global $post;
	$post_type = $post->post_type;
	$arr = array( 'post' => 'single','page' => 'page' );
	$customize = get_theme_mod( 'ric_stt_disable_at_'.$arr[$post_type] );
	wp_nonce_field( 'ric_stt_meta_box_nonce','ric_stt_meta_box_nonce' );
	$chk = get_post_meta( $post->ID, '_ric_stt_title', true );
	$chk = $chk != '' ? $chk : !$customize;
	?>
	<input type="hidden" name="ric_stt_title" value="0" />
	<p><input type="checkbox" name="ric_stt_title" id="ric_stt_title" value="1" <?php checked( $chk ); ?>/><span><?php _e( 'Display title','stt' ); ?></span></p>
	<?php
}
add_action( 'save_post', 'ric_stt_save_meta_box' );
//Save metaboxes data
function ric_stt_save_meta_box( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( !isset( $_POST['ric_stt_meta_box_nonce'] ) || !isset( $_POST['ric_stt_title'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['ric_stt_meta_box_nonce'], 'ric_stt_meta_box_nonce' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_posts', $post_id ) ) {
		return;
	}
	/* OK, it's safe for us to save the data now. */
	// Update the meta field in the database.
	update_post_meta( $post_id,'_ric_stt_title',esc_attr( $_POST['ric_stt_title'] ) );

}