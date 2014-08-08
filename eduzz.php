<?php

/*
Plugin Name: Eduzz Plugin
Description: Eduzz Plugin.
Version: 1.00
Author: Oxy Kay
Author URI: 
License: GPLv2 or later
Network: true
*/

//require_once 'class/github-updater.php'; //adding class

require_once( 'class/BFIGitHubPluginUploader.php' );

if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'epachelle', "wp_eduzz_plugin" );
}



//sites menu
add_action('admin_menu', 'menu_pages');

function menu_pages() {
	
add_menu_page(__('Eduzz', 'eduzz-plugin'), __('Eduzz', 'eduzz-plugin'), 'manage_options', 'eduzzplugin', 'eduzz_plugin_page', '', 119);	
	
}

function eduzz_plugin_page() {

global $transient;
var_dump($transient);
	
}
	
?>
