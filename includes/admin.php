<?php
/**
 * Adds our admin menus, and some stylesheets and JavaScript to the admin head.
 * @package my-reading-library
 */

/**
 * Adds our stylesheets and JS to admin pages.
 */

require_once MRL_ADMIN_DIR . 'admin-add.php';
require_once MRL_ADMIN_DIR . 'admin-manage.php';
require_once MRL_ADMIN_DIR . 'admin-options.php';

/**
 * Manages the various admin pages My Reading Library uses.
 */
function mrl_add_pages() {
    $options = get_option('MyReadingLibraryOptions');

	if (!$options['multiuserMode']) {
		
		if ( $options['menuLayout'] == MRL_MENU_SINGLE ) {

			add_menu_page(__('My Library', MRLTD), __('My Library', MRLTD), 'manage_options', 'library_menu', 'mrl_add_book');
			add_submenu_page('library_menu', __('Add a Book', MRLTD), __('Add a Book', MRLTD), 'manage_options', 'library_menu', 'mrl_add_book');
			add_submenu_page('library_menu', __('Manage Books', MRLTD), __('Manage Books', MRLTD), 'manage_options', 'manage_books', 'mrl_manage_books');
			add_submenu_page('library_menu', __('Library Options', MRLTD), __('Options', MRLTD), 'manage_options', 'mrl_options', 'mrl_manage_options');
		
		} else {

			add_submenu_page('post-new.php', __('Add to Library', MRLTD), __('Add to Library', MRLTD), 'manage_options', 'library_menu', 'mrl_add_book');
			add_management_page(__('Manage Library', MRLTD), __('Manage Library', MRLTD), 'manage_options', 'manage_books', 'mrl_manage_books');
			add_options_page(__('Library Options', MRLTD), __('Library Options', MRLTD), 'manage_options', 'mrl_options', 'mrl_manage_options');

		}

	} else {

		if ( $options['menuLayout'] == MRL_MENU_SINGLE ) {

			add_menu_page(__('My Library', MRLTD), __('My Library', MRLTD), 'publish_posts', 'library_menu', 'mrl_add_book');
			add_submenu_page('library_menu', __('Add a Book', MRLTD), __('Add a Book', MRLTD), 'publish_posts', 'library_menu', 'mrl_add_book');
			add_submenu_page('library_menu', __('Manage Books', MRLTD), __('Manage Books', MRLTD), 'publish_posts', 'manage_books', 'mrl_manage_books');
			add_submenu_page('library_menu', __('Library Options', MRLTD), __('Options', MRLTD), 'manage_options', 'mrl_options', 'mrl_manage_options');
		
		} else {

			add_submenu_page('post-new.php', __('Add to Library', MRLTD), __('Add to Library', MRLTD), 'publish_posts', 'library_menu', 'mrl_add_book');
			add_management_page(__('Manage Library', MRLTD), __('Manage Library', MRLTD), 'publish_posts', 'manage_books', 'mrl_manage_books');
			add_options_page(__('Library Options', MRLTD), __('Library Options', MRLTD), 'manage_options', 'mrl_options', 'mrl_manage_options');

		}
		
	}

}

add_action('admin_menu', 'mrl_add_pages');
	
?>