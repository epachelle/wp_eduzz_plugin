<?php

/*
Plugin Name: Eduzz Plugin
Description: Eduzz Plugin.
Version: 1.03
Author: Codepress
Author URI: http://codepress.nl
License: GPLv2 or later
*/

require_once 'class/github-updater.php'; //adding class

if ( !class_exists( 'GitHub_Plugin_Updater' ) ) :

/**
 * Register a new GitHub plugin
 *
 * @param array $config
 */
function ceduzz_github_plugin_updater_register( $config ) {
	$upd = new GitHub_Plugin_Updater( $config );
	
	//var_dump($upd->get_local_version());
}

//ok, now we set, create plugin info

function ceduzz_github_updater() {
	
if ( !function_exists( 'ceduzz_github_plugin_updater_register' ) )
return false;

ceduzz_github_plugin_updater_register( array(
'owner' => 'epachelle',
'repo' => 'wp/eduzz',
'slug' => 'wp/eduzz/eduzz.php', // defaults to the repo value ('repo/repo.php')
) );
}
add_action( 'plugins_loaded', 'ceduzz_github_updater' );




endif;

?>
