<?php

/*
Plugin Name: EduzzWordpress Plugin
Description: EduzzWordpress Plugin
Version: 1.03
Author: Oxy Kay
Author URI: http://www.eduzz.com/ajuda/
License: GPLv2 or later
Network: true
*/


require_once( 'class/BFIGitHubPluginUploader.php' );

if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'epachelle', "wp_eduzz_plugin" );
}

if (!class_exists('eduzz_funnel')) {
	
define(EDUZZ_VER, '1.00'); //change manually !!!
define(EDUZZ_HELP, 'http://www.eduzz.com/ajuda/'); //change manually !!!
define(EDUZZ_SITE, 'http://www.eduzz.com/'); //change manually !!!
define(EDUZZ_LOGO, get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)) . '/images/'); //change manually !!!

class eduzz_funnel {
	
public function __construct() {
	
	//sites menu - reserved
	add_action('admin_menu', array($this, 'menu_pages'));
	
	add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
	add_action( 'save_post', array( $this, 'save' ) );
	
	add_action( 'wp_footer', array( $this, 'footer_code' ) );
	
	
} //eof construct


function menu_pages() {
	
add_menu_page(__('Eduzz', 'eduzz_textdomain'), __('Eduzz', 'eduzz-plugin'), 'manage_options', 'eduzzplugin', array($this,'eduzz_plugin_page'), EDUZZ_LOGO.'icon.png', 119);	
	
}

function eduzz_plugin_page() {

echo '
<div id="wrap">
<img src="'.EDUZZ_LOGO.'logo.png" style="float:right; padding-top:2%; padding-right:2%" width="20%" height="20%" />
<h2>'.__( 'EduzzWordpress Plugin', 'eduzz_textdomain' ).'</h2>
<p>'.__( 'Version:', 'eduzz_textdomain' ).' '.EDUZZ_VER.'</p>
<p>'.__( 'Site:', 'eduzz_textdomain' ).' <br /><a target="_blank" href="'.EDUZZ_SITE.'">'.EDUZZ_SITE.'</a></p>
<p>'.__( 'Help and How to Use:', 'eduzz_textdomain' ).' <br /><a target="_blank" href="'.EDUZZ_HELP.'">'.EDUZZ_HELP.'</a></p>
</div>
';	
}


public function footer_code() {

global $post;
$value = get_post_meta( $post->ID, '_eduzz_funnel_data', true );
$email = $_POST['EMAIL'];

//having anything?
if (!empty($value) and !empty($email)) {
	$data = str_replace('[VMAIL=XXX]',$email,$value);
	echo $data;
}	
	
}

/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post_type ) {
            $post_types = array('post', 'page');     //limit meta box to certain post types
            if ( in_array( $post_type, $post_types )) {
		add_meta_box(
			'some_meta_box_name'
			,__( 'Eduzz Funnel Script', 'eduzz_textdomain' )
			,array( $this, 'render_meta_box_content' )
			,$post_type
			,'advanced'
			,'high'
		);
            }
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['myplugin_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$mydata =  $_POST['eduzz_funnel_data'] ;

		// Update the meta field.
		update_post_meta( $post_id, '_eduzz_funnel_data', $mydata );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_eduzz_funnel_data', true );

		// Display the form, using the current value.
		echo '<textarea style="width:95%; height:120px;" placeholder="';
		_e( 'Paste here proper script to track the prospect activity in the funnel. This script can be found in your in the funnel list', 'eduzz_textdomain' );
		echo '" id="eduzz_funnel_data" name="eduzz_funnel_data">' .  $value  . '</textarea>';

}

//activation 
public function activate(){
}

public function deactivate(){
}

	
}} //eof class

//load class, assign it to $lesson
if (class_exists('eduzz_funnel')) {
	
	$eduzz = new eduzz_funnel();
	
	register_activation_hook(__FILE__, array($eduzz, 'activate'));
	register_deactivation_hook(__FILE__, array($eduzz, 'deactivate')); 


//deal with metabox
function load_eduzz() {
	
new eduzz_funnel();
}
	if ( is_admin() ) {
    add_action( 'load-post.php', 'load_eduzz' );
    add_action( 'load-post-new.php', 'load_eduzz' );
	}	
}  	
?>
