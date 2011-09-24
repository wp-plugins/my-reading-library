<?php
/**
 * Adds our widget.
 * @package my-reading-library
 */

function mrl_widget($args) {
    extract($args);

    $options = get_option('MyReadingLibraryWidget');
    $title = $options['title'];

    echo $before_widget . $before_title . $title . $after_title;
    if( !defined('MY_READING_LIBRARY_VERSION') || floatval(MY_READING_LIBRARY_VERSION) < 1.0 ) {
        echo "<p>" . _e("You don't appear to have the My Reading Library plugin installed, or have an old version; you'll need to install or upgrade before this widget can display your data.", MRLTD) . "</p>";
    } else {
        mrl_load_template('sidebar.php');
    }
    echo $after_widget;
}

function mrl_widget_control() {
    $options = get_option('MyReadingLibraryWidget');

    if ( !is_array($options) )
        $options = array('title' => 'My Reading Library');

    if ( $_POST['MyReadingLibrarySubmit'] ) {
        $options['title'] = htmlspecialchars(stripslashes($_POST['MyReadingLibraryTitle']), ENT_QUOTES, 'UTF-8');
        update_option('MyReadingLibraryWidget', $options);
    }

    $title = htmlspecialchars($options['title'], ENT_QUOTES, 'UTF-8');

    echo '
		<p style="text-align:right;">
			<label for="MyReadingLibraryTitle">Title:
				<input style="width: 200px;" id="MyReadingLibraryTitle" name="MyReadingLibraryTitle" type="text" value="'.$title.'" />
			</label>
		</p>
	<input type="hidden" id="MyReadingLibrarySubmit" name="MyReadingLibrarySubmit" value="1" />
	';
}

function mrl_widget_init() {
    if ( !function_exists('register_sidebar_widget') )
        return;

    register_sidebar_widget(__('My Reading Library', MRLTD), 'mrl_widget', null, 'my-reading-library');
    register_widget_control(__('My Reading Library', MRLTD), 'mrl_widget_control', 300, 100, 'my-reading-library');
}

add_action('plugins_loaded', 'mrl_widget_init');

?>
