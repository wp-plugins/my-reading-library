<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function mrl_delete_plugin() {
	global $wpdb;

	delete_option( 'MyReadingLibraryOptions' );
	delete_option( 'MyReadingLibraryVersions' );
	delete_option( 'MyReadingLibraryWidget' );

	$table_name = $wpdb->prefix . "my_reading_library";
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

mrl_delete_plugin();

?>