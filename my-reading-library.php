<?php
/*
Plugin Name: My Reading Library
Version: 1.0
Plugin URI: http://www.affordable-techsupport.com/code/
Description: My Reading Library displays books you have read, are reading, and hope to read, in the sidebar with cover art fetched automatically from Amazon. It allows you develop a library, show the book details, your progress, rate the book, and add link to a WP post of your book review. This Plugin is a heavily modified version of the Now Reading Plugin(s) (Original, Reloaded, Redux).
Author: Scott Olson
Author URI: http://www.affordable-techsupport.com/
License: GPL2
*/
/*  Copyright 2011  Scott Olson  (email : scott@affordable-techsupport.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

define('MY_READING_LIBRARY_VERSION', '1.0');
define('MY_READING_LIBRARY_DB', 54);
define('MY_READING_LIBRARY_OPTIONS', 22);
define('MY_READING_LIBRARY_REWRITE', 8);
define('MRLTD', 'my-reading-library');
define('MRL_BASE_DIR', dirname(__FILE__).'/');
define('MRL_INCLUDES_DIR', MRL_BASE_DIR.'includes/');
define('MRL_TEMPLATES_DIR', MRL_BASE_DIR.'templates/');
define('MRL_ADMIN_DIR', MRL_BASE_DIR.'admin/');
define('MRL_LANG_DIR', MRL_BASE_DIR.'languages/');
define('MRL_XML_DIR', MRL_BASE_DIR.'bookxml/');
define('MRL_MENU_SINGLE', 4);
define('MRL_MENU_MULTIPLE', 2);

/**
 * Load our I18n domain.
 */
add_action('init', 'mlr_init');
function mlr_init() {
	load_plugin_textdomain(MRLTD, false, MRL_LANG_DIR);
}

/**
 * Array of the statuses that books can be.
 * @global array $GLOBALS['mrl_statuses']
 * @name $mrl_statuses
 */
$mrl_statuses = apply_filters('mrl_statuses', array(
    'unread'	=> __('Future Book', MRLTD),
    'onhold'	=> __('Book on Hold', MRLTD),
    'reading'	=> __('Current Book', MRLTD),
    'read'		=> __('Completed Book', MRLTD)
));

/**
 * Array of the domains we can use for Amazon.
 * @global array $GLOBALS['mrl_domains']
 * @name $mrl_domains
 */
$mrl_domains = array(
    '.com'		=> __('International', MRLTD),
    '.co.uk'	=> __('United Kingdom', MRLTD),
    '.fr'		=> __('France', MRLTD),
    '.de'		=> __('Germany', MRLTD),
    '.co.jp'	=> __('Japan', MRLTD),
    '.ca'		=> __('Canada', MRLTD)
);

// Include other functionality
require_once MRL_INCLUDES_DIR . 'compat.php';
require_once MRL_INCLUDES_DIR . 'rewrite.php';
require_once MRL_INCLUDES_DIR . 'books.php';
require_once MRL_INCLUDES_DIR . 'amazon.php';
require_once MRL_INCLUDES_DIR . 'admin.php';
require_once MRL_INCLUDES_DIR . 'filters.php';
require_once MRL_INCLUDES_DIR . 'functions.php';
require_once MRL_INCLUDES_DIR . 'widget.php';

/**
 * Checks if the install needs to be run by checking the `MyReadingLibraryVersions` option, which stores the current installed database, options and rewrite versions.
 */
function mrl_check_versions()
{
    $versions = get_option('MyReadingLibraryVersions');
    if (empty($versions) ||
		$versions['db'] < MY_READING_LIBRARY_DB ||
		$versions['options'] < MY_READING_LIBRARY_OPTIONS ||
		$versions['rewrite'] < MY_READING_LIBRARY_REWRITE)
    {
		mrl_install();
    }
}
add_action('init', 'mrl_check_versions');
add_action('plugins_loaded', 'mrl_check_versions');

function mrl_check_api_key() {
    $options = get_option('MyReadingLibraryOptions');
    $AWSAccessKeyId = $options['AWSAccessKeyId'];
    $SecretAccessKey = $options['SecretAccessKey'];

    if (empty($AWSAccessKeyId) || empty($SecretAccessKey)) {

        function mrl_key_warning() {
            echo "
			<div id='mrl_key_warning' class='updated fade'><p><strong>".__('My Reading Library has detected a problem.', MRLTD)."</strong> ".sprintf(__('You are missing one of both: Amazon Web Services Access Key ID or Secret Access Key. Enter them <a href="%s">here</a>.', MRLTD), "admin.php?page=mrl_options")."</p></div>
			";
        }
        add_action('admin_notices', 'mrl_key_warning');
        return;
    }
}
add_action('init','mrl_check_api_key');


/**
 * Handler for the activation hook. Installs/upgrades the database table and adds/updates the MyReadingLibraryOptions option.
 */
function mrl_install() {
    global $wpdb, $wp_rewrite, $wp_version;

    if ( version_compare('3.0', $wp_version) == 1 && strpos($wp_version, 'wordpress-mu') === false ) {
        echo "
		<p>".__('(My Reading Library only works with WordPress 3.0 and above)', MRLTD)."</p>
		";
        return;
    }

    // WP's dbDelta function takes care of installing/upgrading our DB table.
    $upgrade_file = file_exists(ABSPATH . 'wp-admin/includes/upgrade.php') ? ABSPATH . 'wp-admin/includes/upgrade.php' : ABSPATH . 'wp-admin/upgrade-functions.php';
    require_once $upgrade_file;
    // Until the nasty bug with duplicate indexes is fixed, we should hide dbDelta output.
    ob_start();
    dbDelta("
	CREATE TABLE {$wpdb->prefix}my_reading_library (
	b_id bigint(20) NOT NULL auto_increment,
	b_added datetime,
	b_started datetime,
	b_finished datetime,
	b_title VARCHAR(100) NOT NULL,
	b_nice_title VARCHAR(100) NOT NULL,
	b_author VARCHAR(100) NOT NULL,
	b_nice_author VARCHAR(100) NOT NULL,
	b_image text,
	b_limage text,
	b_asin varchar(12) NOT NULL,
	b_status VARCHAR(8) NOT NULL default 'read',
	b_tpages smallint(6) default '0',
	b_cpages smallint(6) default '0',
	b_rating tinyint(4) default '0',
	b_post bigint(20) default '0',
	b_visibility tinyint(1) default '1',
	b_reader tinyint(4) NOT NULL default '1',
	PRIMARY KEY  (b_id),
	INDEX permalink (b_nice_author, b_nice_title),
	INDEX title (b_title),
	INDEX author (b_author)
	);
        ");
    $log = ob_get_contents();
    ob_end_clean();

    $log_file = dirname(__FILE__) . '/install-log-' . date('Y-m-d') . '.txt';
    if ( is_writable($log_file) ) {
        $fh = @fopen( $log_file, 'w' );
        if ( $fh ) {
            fwrite($fh, strip_tags($log));
            fclose($fh);
        }
    }

    $defaultOptions = array(
        'formatDate'	=> 'n/j/Y',
		'ignoreTime'	=> false,
		'hideAddedDate'	=>	false,
        'associate'		=> 'passforchrimi-20',
        'domain'		=> '.com',
        'imageSize'		=> 'Small',
		'limageSize'	=> 'Medium',
        'httpLib'		=> 'snoopy',
        'useModRewrite'	=> false,
        'debugMode'		=> false,
        'menuLayout'	=> MRL_MENU_SINGLE,
        'booksPerPage'  => 10,
        'defBookCount'  => 5,
		'hideCurrentBooks' => false,
		'hidePlannedBooks' => false,
		'hideFinishedBooks' => true,
		'hideBooksonHold' => true,
		'hideViewLibrary' => false,
		'templateBase' => 'default_templates/',
        'permalinkBase' => 'my-library/'
    );
    add_option('MyReadingLibraryOptions', $defaultOptions);

    // Merge any new options to the existing ones.
    $options = get_option('MyReadingLibraryOptions');
    $options = array_merge($defaultOptions, $options);
    update_option('myReadingLibraryOptions', $options);

	// May be unset if called during plugins_loaded action.
	if (isset($wp_rewrite))
    {
		// Update our .htaccess file.
		$wp_rewrite->flush_rules();
	}

    // Update our nice titles/authors.
    $books = $wpdb->get_results("
	SELECT
		b_id AS id, b_title AS title, b_author AS author
	FROM
        {$wpdb->prefix}my_reading_library
	WHERE
		b_nice_title = '' OR b_nice_author = ''
        ");
    foreach ( (array) $books as $book ) {
        $nice_title = $wpdb->escape(sanitize_title($book->title));
        $nice_author = $wpdb->escape(sanitize_title($book->author));
        $id = intval($book->id);
        $wpdb->query("
		UPDATE
            {$wpdb->prefix}my_reading_library
		SET
			b_nice_title = '$nice_title',
			b_nice_author = '$nice_author'
		WHERE
			b_id = '$id'
            ");
    }

    // Set an option that stores the current installed versions of the database, options and rewrite.
    $versions = array('db' => MY_READING_LIBRARY_DB, 'options' => MY_READING_LIBRARY_OPTIONS, 'rewrite' => MY_READING_LIBRARY_REWRITE);
    update_option('MyReadingLibraryVersions', $versions);
}
register_activation_hook('my-reading-library/my-reading-library.php', 'mrl_install');

/**
 * Checks to see if the library/book permalink query vars are set and, if so, loads the appropriate templates.
 */
function library_init() {
    global $wp, $wpdb, $q, $query, $wp_query;

    $wp->parse_request();

    if ( is_my_reading_library_page() )
        add_filter('wp_title', 'mrl_page_title');
    else
        return;

    if ( get_query_var('my_reading_library_library') ) {
        // Library page:
        mrl_load_template('library.php');
        die;
    }

    if ( get_query_var('my_reading_library_id') ) {
    // Book permalink:
        $GLOBALS['mrl_id'] = intval(get_query_var('my_reading_library_id'));

        $load = mrl_load_template('single.php');
        if ( is_wp_error($load) )
            echo $load->get_error_message();

        die;
    }

    if ( get_query_var('my_reading_library_page') ) {
    // get page name from query string:
        $mrlr_page = get_query_var('my_reading_library_page');

        $load = mrl_load_template($mrlr_page);
        if ( is_wp_error($load) )
            echo $load->get_error_message();

        die;
    }

    if ( get_query_var('my_reading_library_author') && get_query_var('my_reading_library_title') ) {
    // Book permalink with title and author.
        $author				= $wpdb->escape(urldecode(get_query_var('my_reading_library_author')));
        $title				= $wpdb->escape(urldecode(get_query_var('my_reading_library_title')));
        $GLOBALS['mrl_id']	= $wpdb->get_var("
		SELECT
			b_id
		FROM
            {$wpdb->prefix}my_reading_library
		WHERE
			b_nice_title = '$title'
			AND
			b_nice_author = '$author'
            ");

        $load = mrl_load_template('single.php');
        if ( is_wp_error($load) )
            echo $load->get_error_message();

        die;
    }
}
add_action('template_redirect', 'library_init');

/**
 * Loads the given filename from The My Reading Library templates directory.
 * @param string $filename The filename of the template to load.
 */
function mrl_load_template( $filename ) {
    $template_option = get_option('myReadingLibraryOptions');
	$template_directory = $template_option['templateBase'];

    $template = MRL_TEMPLATES_DIR . "$template_directory" . "$filename";

    if ( !file_exists($template) )
        return new WP_Error('template-missing', sprintf(__("Oops! The template file %s could not be found in the My Reading Library default_template or custom_template directories.", MRLTD), "<code>$filename</code>"));

    load_template($template);
}

/**
 * Provides a simple API for themes to load the sidebar template.
 */
function mrl_display() {
    mrl_load_template('sidebar.php');
}

/**
 * Adds our details to the title of the page - book title/author, "Library" etc.
 */
function mrl_page_title( $title ) {
    global $wp, $wp_query;
    $wp->parse_request();

    $title = '';

    if ( get_query_var('my_reading_library_library') )
        $title = 'My Library';

    if ( get_query_var('my_reading_library_id') ) {
        $book = get_book(intval(get_query_var('my_reading_library_id')));
        $title = $book->title . ' by ' . $book->author;
    }

    if ( !empty($title) ) {
        $title = apply_filters('my_reading_library_page_title', $title);
        $separator = apply_filters('my_reading_library_page_title_separator', ' | ');
        return $title.$separator;
    }
    return '';
}

/**
 * Adds information to the header for future statistics purposes.
 */
function mrl_header_stats() {
    echo '
	<meta name="my_reading_library-version" content="' . MY_READING_LIBRARY_VERSION . '" />
	';
}
add_action('wp_head', 'mrl_header_stats');

if ( !function_exists('robm_dump') ) {
/**
 * Dumps a variable in a pretty way.
 */
    function robm_dump() {
        echo '<pre style="border:1px solid #000; padding:5px; margin:5px; max-height:150px; overflow:auto;" id="' . md5(serialize($object)) . '">';
        $i = 0; $args = func_get_args();
        foreach ( (array) $args as $object ) {
            if ( $i == 0 && count($args) > 1 && is_string($object) )
                echo "<h3>$object</h3>";
            var_dump($object);
            $i++;
        }
        echo '</pre>';
    }
}

?>