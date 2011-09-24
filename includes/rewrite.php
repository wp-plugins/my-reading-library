<?php
/**
 * URL/mod_rewrite functions
 * @package my-reading-library
 */

/**
 * Handles our URLs, depending on what menu layout we're using
 * @package my-reading-library
 */
class mrl_url {
/**
 * The current URL scheme.
 * @access public
 * @var array
 */
    var $urls;

    /**
     * The scheme for a multiple menu layout.
     * @access private
     * @var array
     */
    var $multiple;
    /**
     * The scheme for a single menu layout.
     * @access private
     * @var array
     */
    var $single;

    /**
     * Constructor. Populates {@link $multiple} and {@link $single}.
     */
    function mrl_url() {
        $this->multiple = array(
            'add'		=> get_option('siteurl') . '/wp-admin/post-new.php?page=library_menu',
            'manage'	=> get_option('siteurl') . '/wp-admin/admin.php?page=manage_books',
            'options'	=> get_option('siteurl') . '/wp-admin/options-general.php?page=mrl_options'
        );
        $this->single = array(
            'add'		=> get_option('siteurl') . '/wp-admin/admin.php?page=library_menu',
            'manage'	=> get_option('siteurl') . '/wp-admin/admin.php?page=manage_books',
            'options'	=> get_option('siteurl') . '/wp-admin/admin.php?page=mrl_options'
        );
    }

    /**
     * Loads the given scheme, populating {@link $urls}
     * @param integer $scheme The scheme to use, either MRL_MENU_SINGLE or MRL_MENU_MULTIPLE
     */
    function load_scheme( $option ) {
        if ( $option == MRL_MENU_SINGLE )
            $this->urls = $this->single;
        else
            $this->urls = $this->multiple;
    }
}
/**
 * Global singleton to access our current scheme.
 * @global mrl_url $GLOBALS['mrl_url']
 * @name $mrl_url
 */
$mrl_url	= new mrl_url();
$options	= get_option('MyReadingLibraryOptions');
$mrl_url->load_scheme($options['menuLayout']);

/**
 * Registers our query vars so we can redirect to the library and book permalinks.
 * @param array $vars The existing array of query vars
 * @return array The modified array of query vars with our additions.
 */
function mrl_query_vars( $vars ) {
    $vars[] = 'my_reading_library_library';
    $vars[] = 'my_reading_library_id';
    $vars[] = 'my_reading_library_page';   
    $vars[] = 'my_reading_library_title';
    $vars[] = 'my_reading_library_author';
    $vars[] = 'my_reading_library_reader'; //in order to filter books by reader
    return $vars;
}
add_filter('query_vars', 'mrl_query_vars');

/**
 * Adds our rewrite rules for the library and book permalinks to the regular WordPress ones.
 * @param array $rules The existing array of rewrite rules we're filtering
 * @return array The modified rewrite rules with our additions.
 */
function mrl_mod_rewrite( $rules ) {
    $options = get_option('MyReadingLibraryOptions');
    add_rewrite_rule(preg_quote($options['permalinkBase']) . '([0-9]+)/?$', 'index.php?my_reading_library_id=$matches[1]', 'top');
    add_rewrite_rule(preg_quote($options['permalinkBase']) . 'page/([^/]+)/?$', 'index.php?my_reading_library_page=$matches[1]', 'top');   
    add_rewrite_rule(preg_quote($options['permalinkBase']) . 'reader/([^/]+)/?$', 'index.php?my_reading_library_reader=$matches[1]', 'top');
    add_rewrite_rule(preg_quote($options['permalinkBase']) . '([^/]+)/([^/]+)/?$', 'index.php?my_reading_library_author=$matches[1]&my_reading_library_title=$matches[2]', 'top');
    add_rewrite_rule(preg_quote($options['permalinkBase']) . '([^/]+)/?$', 'index.php?my_reading_library_author=$matches[1]', 'top');
    add_rewrite_rule(preg_quote($options['permalinkBase']) . '?$', 'index.php?my_reading_library_library=1', 'top');
}
add_action('init', 'mrl_mod_rewrite');

/**
 * Returns true if we're on a My Reading Library page.
 */
function is_my_reading_library_page() {
    global $wp;
    $wp->parse_request();

    return (
    get_query_var('my_reading_library_library') ||
        get_query_var('my_reading_library_id')      ||
        get_query_var('my_reading_library_page')    ||        
        get_query_var('my_reading_library_title')   ||
        get_query_var('my_reading_library_author')  ||
		get_query_var('my_reading_library_reader')
	);  
}

?>