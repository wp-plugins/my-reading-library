<?php
/**
 * Admin interface for managing options.
 * @package my-reading-library
 */

if( !isset($_SERVER['REQUEST_URI']) ) {
    $arr = explode("/", $_SERVER['PHP_SELF']);
    $_SERVER['REQUEST_URI'] = "/" . $arr[count($arr) - 1];
    if ( !empty($_SERVER['argv'][0]) )
        $_SERVER['REQUEST_URI'] .= "?{$_SERVER['argv'][0]}";
}

/**
 * Creates the options admin page and manages the updating of options.
 */
function mrl_manage_options() {

    global $wpdb, $mrl_domains;

    $options = get_option('MyReadingLibraryOptions');

    if ( !empty($_GET['curl']) ) {
        echo '
			<div id="message" class="error fade">
				<p><strong>' . __("Oops!", MRLTD) . '</strong></p>
				<p>' . __("You don\'t appear to have cURL installed!", MRLTD) . '</p>
				<p>' . __("Since you can\'t use cURL, I\'ve switched your HTTP Library setting to <strong>Snoopy</strong> instead, which should work.", MRLTD) . '</p>
			</div>
		';
    }

    if ( !empty($_GET['imagesize']) ) {
        echo '
			<div id="message" class="error fade">
				<p><strong>' . __("Oops!", MRLTD) . '</strong></p>
				<p>' . __("Naughty naughty! That wasn\'t a valid value for the image size setting!", MRLTD) . '</p>
				<p>' . __("Don\'t worry, I\'ve set it to Small for you.", MRLTD) . '</p>
			</div>
		';
    }

// Added Begin
    if ( !empty($_GET['limagesize']) ) {
        echo '
			<div id="message" class="error fade">
				<p><strong>' . __("Oops!", MRLTD) . '</strong></p>
				<p>' . __("Naughty naughty! That wasn\'t a valid value for the image size setting!", MRLTD) . '</p>
				<p>' . __("Don\'t worry, I\'ve set it to Medium for you.", MRLTD) . '</p>
			</div>
		';
    }
// Added End

    if( !strstr($_SERVER['REQUEST_URI'], 'wp-admin/options') && $_GET['updated'] ) {
        echo '
			<div id="message" class="updated fade">
				<p><strong>' . __("Options Saved", MRLTD) . '</strong></p>
			</div>
		';
    }

    echo '
	<div class="wrap">

		<h2>' . __("My Reading Library", MRLTD) . '</h2>
	';

    echo '
		<form method="post" action="' . get_option('siteurl') . '/wp-content/plugins/my-reading-library/admin/function-options.php">
	';

    if ( function_exists('wp_nonce_field') )
        wp_nonce_field('my-reading-library-update-options');

    echo '
		<table class="form-table" width="100%" cellspacing="2" cellpadding="5">
			<tr valign="top">
				<th scope="row">' . __('AWS Access Key ID', MRLTD) . ':</th>
				<td>
					<input type="text" size="50" name="AWSAccessKeyId" value="' . htmlentities($options['AWSAccessKeyId'], ENT_QUOTES, "UTF-8") . '" />
					<p>
					' . sprintf(__("The Amazon Web Services Access Key ID is required to add books from Amazon: <a target='%s' href='%s'>free registration</a>.", MRLTD), "_blank", "https://aws-portal.amazon.com/gp/aws/developer/registration/index.html") . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('AWS Secret Access Key', MRLTD) . ':</th>
				<td>
					<input type="text" size="75" name="SecretAccessKey" value="' . htmlentities($options['SecretAccessKey'], ENT_QUOTES, "UTF-8") . '" />
					<p>
					' . sprintf(__("The Amazon Web Services Secret Access Key is required to add books from Amazon: <a target='%s' href='%s'>free registration</a>.", MRLTD), "_blank", "https://aws-portal.amazon.com/gp/aws/developer/registration/index.html") . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Amazon Associates Tracking ID', MRLTD) . ':</th>
				<td>
					<input type="text" name="associate" value="' . htmlentities($options['associate'], ENT_QUOTES, "UTF-8") . '" />
					<p>
					' . __("The Amazon Associates Tracking ID is required in order to link to Amazon book product pages using the <code>book_url()</code> template tag. This enables you to earn commissions if your visitors purchase the linked books.", MRLTD) . '
					</p>
					<p>
					' . sprintf(__("If you don't have an Amazon Associates Tracking ID, you can either <a target='%s' href='%s'>register</a> or consider entering mine (if you're feeling generous): <strong>%s</strong>", MRLTD), "_blank", "http://associates.amazon.com", "passforchrimi-20") . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Amazon Domain', MRLTD) . ':</th>
				<td>
					<select name="domain">
	';

    foreach ( (array) $mrl_domains as $domain => $country ) {
        if ( $domain == $options['domain'] )
            $selected = ' selected="selected"';
        else
            $selected = '';

        echo "<option value='$domain'$selected>$country (Amazon$domain)</option>";
    }

    echo '

					</select>
					<p>
					' . __("If you choose to link to your book's product page on Amazon.com using the <code>book_url()</code> template tag, you can specify which country-specific Amazon site to link to. My Reading Library will also use this domain when searching.", MRLTD) . '
					</p>
					<p>
					' . __("NOTE: If you have country-specific books in your catalogue and then change your domain setting, some old links might stop working.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Widget Book Image', MRLTD) . ':</th>
				<td>
					<select name="image_size">
						<option' . ( ($options['imageSize'] == 'Small') ? ' selected="selected"' : '' ) . ' value="Small">' . __("Small", MRLTD) . '</option>
						<option' . ( ($options['imageSize'] == 'Medium') ? ' selected="selected"' : '' ) . ' value="Medium">' . __("Medium", MRLTD) . '</option>
						<option' . ( ($options['imageSize'] == 'Large') ? ' selected="selected"' : '' ) . ' value="Large">' . __("Large", MRLTD) . '</option>
					</select>
					<p>
					' . __("Adjusts the size (small, medium, large) of the Amazon.com book images used in the <code>Widget</code>.", MRLTD) . '
					</p>
					<p>
					' . __("NOTE: This change will only be applied to books you add from this point onwards.", MRLTD) . '
					</p>
				</td>
			</tr>';

// Added Begin
	echo '
			<tr valign="top">
				<th scope="row">' . __('Library Book Image', MRLTD) . ':</th>
				<td>
					<select name="limage_size">
						<option' . ( ($options['limageSize'] == 'Small') ? ' selected="selected"' : '' ) . ' value="Small">' . __("Small", MRLTD) . '</option>
						<option' . ( ($options['limageSize'] == 'Medium') ? ' selected="selected"' : '' ) . ' value="Medium">' . __("Medium", MRLTD) . '</option>
						<option' . ( ($options['limageSize'] == 'Large') ? ' selected="selected"' : '' ) . ' value="Large">' . __("Large", MRLTD) . '</option>
					</select>
					<p>
					' . __("Adjusts the size (small, medium, large) of the Amazon.com book images used in the <code>Library</code>.", MRLTD) . '
					</p>
					<p>
					' . __("NOTE: This change will only be applied to books you add from this point onwards.", MRLTD) . '
					</p>
				</td>
			</tr>';
// Added End

	echo '
			<tr valign="top">
				<th scope="row">' . __('Date Format String', MRLTD) . ':</th>
				<td>
					<input type="text" name="format_date" value="' . htmlentities($options['formatDate'], ENT_QUOTES, "UTF-8") . '" />
					<p>
					' . sprintf(__("Determines how to format the book's <code>added</code>, <code>started</code> and <code>finished</code> dates: <a target='%s' href='%s'>acceptable variables</a>.", MRLTD), "_blank", "http://php.net/date") . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Timestamp Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="ignore_time" id="ignore_time"' . ( ($options['ignoreTime']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, <code>added</code>, <code>started</code> and <code>finished</code> dates will be displayed with day precision only, however when time is set, it will be saved.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Added Date Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="hide_added_date" id="hide_added_date"' . ( ($options['hideAddedDate']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, <code>added</code> date will be hidden in the Manager and Book pages", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Admin Menu Layout', MRLTD) . ':</th>
				<td>
					<label for="menu_layout_single">' . __('Single', MRLTD) . '</label>
					<input type="radio" name="menu_layout" id="menu_layout_single" value="single"' . ( ( $options['menuLayout'] == MRL_MENU_SINGLE ) ? ' checked="checked"' : '' ) . ' />
					<br />
					<label for="menu_layout_single">' . __('Multiple', MRLTD) . '</label>
					<input type="radio" name="menu_layout" id="menu_layout_single" value="multiple"' . ( ( $options['menuLayout'] == MRL_MENU_MULTIPLE ) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When set to 'Single', My Reading Library will add a top-level menu with submenus containing the 'Add a Book', 'Manage Books' and 'Options' screens.", MRLTD) . '
					</p>
					<p>
					' . __("When set to 'Multiple', My Reading Library will insert those menus under 'Posts', 'Tools' and 'Settings' respectively.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="books_per_page">' . __("Admin Manage Books", MRLTD) . '</label>:</th>
				<td>
					<input type="text" name="books_per_page" id="books_per_page" style="width:4em;" value="' . ( intval($options['booksPerPage']) ) . '" />
					<p>
					' . __("Limits the total number of books displayed <code>per page</code> within the administrative 'Manage Books' menu.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="def_book_count">' . __("Widget Total Books Displayed", MRLTD) . '</label>:</th>
				<td>
					<input type="text" name="def_book_count" id="def_book_count" style="width:4em;" value="' . ( intval($options['defBookCount']) ) . '" />
					<p>
					' . __("Limits the total number of books displayed within the sidebar widget in all selected categories.", MRLTD) . '
					</p>
				</td>
			</tr>';

// Added Begin
	echo '
			<tr valign="top">
				<th scope="row">' . __('Widget Current Books Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="hide_current_books" id="hide_current_books"' . ( ($options['hideCurrentBooks']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, your <code>Current Books</code> will be hidden within the sidebar widget.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Widget Planned Books Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="hide_planned_books" id="hide_planned_books"' . ( ($options['hidePlannedBooks']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, your <code>Planned Books</code> will be hidden within the sidebar widget.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Widget Completed Books Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="hide_finished_books" id="hide_finished_books"' . ( ($options['hideFinishedBooks']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, your <code>Completed Books</code> will be hidden within the sidebar widget.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Widget Books on Hold Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="hide_books_on_hold" id="hide_books_on_hold"' . ( ($options['hideBooksonHold']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, your <code>Books on Hold</code> will be hidden within the sidebar widget.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __('Widget Complete Library Toggle', MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="hide_view_library" id="hide_view_library"' . ( ($options['hideViewLibrary']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When checked, the <code>Complete Library</code> link/button will be hidden within the sidebar widget.", MRLTD) . '
					</p>
				</td>
			</tr>';
// Added End

	echo '
			<tr valign="top">
				<th scope="row">' . __("Clean Library URLs Toggle", MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="use_mod_rewrite" id="use_mod_rewrite"' . ( ($options['useModRewrite']) ? ' checked="checked"' : '' ) . ' />
					<p>
						' . __("When checked, <code>mod_rewrite</code> enable clean URLs for the book library and book pages (MUST have an Apache webserver with <code>mod_rewrite</code> enabled).", MRLTD) . '
					</p>
					<p>
						' . __("<code>mod_rewrite</code> disabled: <code>.../index.php?my_reading_library_single=true&my_reading_library_author=timothy-keller&my_reading_library_title=the-prodigal-god</code>", MRLTD) . '
					</p>
					<p>
						' . __("<code>mod_rewrite</code> enabled: <code>.../library/timothy-keller/the-prodigal-god/</code>", MRLTD) . '
					</p>
					<p>
						' . sprintf(__("If you enable this option, you MUST have a custom permalink structure set up: <a href='%s'>Options &raquo; Permalinks</a>", MRLTD), 'options-permalink.php') . '
					</p>
					<p>
					' . __("Permalink Base:", MRLTD) . ' ' . htmlentities(get_option('home')) . '/
					<input type="text" name="permalink_base" id="permalink_base" value="' . htmlentities($options['permalinkBase']) . '" /></p>
				</td>
			</tr>';

// Added Begin
	echo '
			<tr valign="top">
				<th scope="row">' . __('Template Directory', MRLTD) . ':</th>
				<td>
					<input type="text" size="75" name="template_base" id="template_base" value="' . htmlentities($options['templateBase'], ENT_QUOTES, "UTF-8") . '" />
					<p>
					' . __("The default template directory is <code>default_templates/</code>. This should not be changed if these template files are compatible with your theme.", MRLTD) . '
					</p>
					<p>
					' . sprintf(__("The custom template directory is <code>custom_templates/</code>. These template files can be customized to suit your theme. You will need to integrate the code calling your theme's header, sidebar, and footer into the <code>library.php</code> and <code>single.php</code> files (there are placement hints within the files) using a <a target='%s' href='%s'>FTP Manager</a> and/or a <a target='%s' href='%s'>Quality File Editor</a>. Your theme's <code>page.php</code> file (or a similar file) will contain the code that will need to integrated. Take note of the code before the <code>div class=content</code> and the code after its corresponding closing <code>div</code>.", MRLTD), '_blank', 'http://filezilla-project.org/', '_blank', 'http://notepad-plus-plus.org/') . '
					</p>
					<p>
					' . __("The template directory for the Twenty Ten Theme is <code>custom_templates/twentyten/</code>.", MRLTD) . '
					</p>
					<p>
					' . __("The template directory for the Twenty Eleven Theme is <code>custom_templates/twentyeleven/</code>.", MRLTD) . '
					</p>
					<p>
					' . __("The template directory for the Weaver 2 Column Theme is <code>custom_templates/weaver/</code>.", MRLTD) . '
					</p>
				</td>
			</tr>';
// Added End

	echo '
			<tr valign="top">
				<th scope="row">' . __("HTTP Library", MRLTD) . ':</th>
				<td>
					<select name="http_lib">
						<option' . ( ($options['httpLib'] == 'snoopy') ? ' selected="selected"' : '' ) . ' value="snoopy">' . __("Snoopy", MRLTD) . '</option>
						<option' . ( ($options['httpLib'] == 'curl') ? ' selected="selected"' : '' ) . ' value="curl">' . __("cURL", MRLTD) . '</option>
					</select>
					<p>
					' . __("If this is confusing, no worries. Unless you're having problems searching for books, the default setting will be fine.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __("Proxy Hostname/Port", MRLTD) . ':</th>
				<td>
					<input type="text" name="proxy_host" id="proxy_host" value="' . $options['proxyHost'] . '" />:<input type="text" name="proxy_port" id="proxy_port" style="width:4em;" value="' . $options['proxyPort'] . '" />
					<p>
					' . __("If this is confusing, no worries. Unless you're having problems searching for books, the default setting will be fine.", MRLTD) . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __("Debug Mode", MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="debug_mode" id="debug_mode"' . ( ($options['debugMode']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . sprintf(__("When checked, My Reading Library will produce debugging output that might help you troubleshoot, solve problems, and <a target='%s' href='%s'>report bugs</a>.", MRLTD), "_blank", "http://www.affordable-techsupport.com/support/") . '
					</p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">' . __("User Mode", MRLTD) . ':</th>
				<td>
					<input type="checkbox" name="multiuser_mode" id="multiuser_mode"' . ( ($options['multiuserMode']) ? ' checked="checked"' : '' ) . ' />
					<p>
					' . __("When unchecked, <code>Single-User Mode</code> is enabled: ONLY <code>Administrators</code> can add books, manage the library, and modify the options.", MRLTD) . '
					</p>
					<p>
					' . __("When checked, <code>Multi-User Mode</code> is enabled: <code>Authors</code> and <code>Editors</code> can add books and manage the books they've added to the library; however, they can not modify the options.", MRLTD) . '
					</p>
					<p>
					' . __("In <code>Multi-User Mode</code>, <code>Administrators</code> can add books, manage all books added to the library (by all users), modify the options, and view/modify the users responsible for reading/reviewing books.", MRLTD) . '
					</p>
				</td>
			</tr>
		</table>

		<input type="hidden" name="update" value="yes" />

		<p class="submit">
			<input type="submit" value="' . __("Update Options", MRLTD) . '" />
		</p>

		</form>

	</div>
	';

}

?>
