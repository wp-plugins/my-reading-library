<?php	
/**
 * Our admin interface for adding books.
 * @package my-reading-library
 */

if ( !function_exists('mrl_add_book') ) {
/**
 * The write admin page deals with the searching for and ultimate addition of books to the database.
 */
    function mrl_add_book() {

        $_POST = stripslashes_deep($_POST);

        global $wpdb;

        $options = get_option('MyReadingLibraryOptions');

        if( !$mrl_url ) {
            $mrl_url = new mrl_url();
            $mrl_url->load_scheme($options['menuLayout']);
        }

        if ( !empty($_GET['error']) ) {
            echo '
			<div id="message" class="error fade">
				<p><strong>' . __("Error adding book!", MRLTD) . '</strong></p>
			</div>
			';
        }

        if ( !empty($_GET['added']) ) {
            echo '
			<div id="message" class="updated fade">
				<p><strong>' . __("Book Added", MRLTD) . '</strong></p>
				<ul>
					<li><a href="' . $mrl_url->urls['manage'] . '">' . __("Manage Books", MRLTD) . ' &raquo;</a></li>
					<li><a href="' . apply_filters('book_edit_url', $mrl_url->urls['manage'] . '&action=editsingle&id=' . intval($_GET['added'])) . '">' . __("Edit This Book", MRLTD) . ' &raquo;</a></li>
					<li><a href="' . library_url(0) . '">' . __("View Library", MRLTD) . ' &raquo;</a></li>
					<li><a href="' . get_option('home') . '">' . __("View Site", MRLTD) . ' &raquo;</a></li>
				</ul>
			</div>
			';
        }

        echo '
		<div class="wrap">

			<h2>' . __("My Reading Library", MRLTD) . '</h2>
		';

        if (  !empty($_POST['u_isbn']) || !empty($_POST['u_author']) || !empty($_POST['u_title']) ) {

            echo '<h3>' . __("Search Results", MRLTD) . '</h3>';

            $isbn	= $_POST['u_isbn'];
            $author	= $_POST['u_author'];
            $title	= $_POST['u_title'];
            if ( !empty($_POST['u_isbn']) )
                $using_isbn = true;

            if ( $using_isbn )
                $results = query_amazon("isbn=$isbn");
            else
                $results = query_amazon("title=$title&author=$author");

            if ( is_wp_error($results) ) {
                foreach ( (array) $results->get_error_codes() as $code ) {
                    if ( $code == 'curl-not-installed' ) {
                        echo '
							<div id="message" class="error fade">
								<p><strong>' . __("Oops!", MRLTD) . '</strong></p>
								<p>' . __("I couldn't fetch the results for your search, because you don't have cURL installed!", MRLTD) . '</p>
								<p>' . __("To solve this problem, please switch your <strong>HTTP Library</strong> setting to <strong>Snoopy</strong>, which works on virtually all server setups.", MRLTD) . '</p>
								<p>' . sprintf(__("You can change your options <a href='%s'>here</a>.", MRLTD), $mrl_url->urls['options']) . '</p>
							</div>
						';
                    }
                }
            } else {
                if ( !$results ) {
                    if ( $using_isbn )
                        echo '<div class="error"><p>' . sprintf(__("Sorry, but amazon%s did not return any results for the ISBN number <code>%s</code>.", MRLTD), $options['domain'], $isbn) . '</p></div>';
                    else
                        echo '<div class="error"><p>' . sprintf(__("Sorry, but amazon%s did not return any results for the book &ldquo;%s&rdquo;", MRLTD), $options['domain'], $title) . '</p></div>';
                } else {
                    if ( $using_isbn )
                        echo '<p>' . sprintf(__("You searched for the ISBN <code>%s</code>. amazon%s returned these results:", MRLTD), $isbn, $options['domain']) . '</p>';
                    else
                        echo '<p>' . sprintf(__("You searched for the book &ldquo;%s&rdquo;. amazon%s returned these results:", MRLTD), $title, $options['domain']) . '</p>';

                    foreach ( (array) $results as $result ) {
                        extract($result);
                        $data = serialize($result);
                        echo '
						<form method="post" action="' . get_option('siteurl') . '/wp-content/plugins/my-reading-library/admin/function-add.php" style="border:1px solid #ccc; padding:5px; margin:5px;">
						';

                        if ( function_exists('wp_nonce_field') )
                            wp_nonce_field('my-reading-library-add');

                        echo '
							<input type="hidden" name="amazon_data" value="' . htmlentities($data, ENT_QUOTES, "UTF-8") . '" />

							<img src="' . htmlentities($image, ENT_QUOTES, "UTF-8") . '" alt="" style="float:left; margin:8px; padding:2px; width:46px; height:70px; border:1px solid #ccc;" />

							<h3>' . htmlentities($title, ENT_QUOTES, "UTF-8") . '</h3>
							' . (($author) ? '<p>' . __("by ", MRLTD) . '<strong>' . htmlentities($author, ENT_QUOTES, "UTF-8") . '</strong></p>' : '<p>(' . __("No author", MRLTD) . ')</p>') . '
							' . (($ed) ? htmlentities($ed, ENT_QUOTES, "UTF-8") . ' ' : '
							') . (($binding) ? htmlentities($binding, ENT_QUOTES, "UTF-8") . ' ' : '
							') . (($date) ? ' - ' . htmlentities($date, ENT_QUOTES, "UTF-8") . ' ' : '
							') . (($publisher) ? ' (' . htmlentities($publisher, ENT_QUOTES, "UTF-8") . ') ' : '
							') . '
							<p style="clear:left;"><input class="button" type="submit" value="' . __("Use This Result", MRLTD) . '" /></p>

						</form>
						';
                    }
                }
            }

        }

        echo '
		<div class="mrl-add-grouping">
		<h3>' . __("Search for a Book to Add", MRLTD) . '</h3>';

        if ( !$thispage )
            $thispage = $mrl_urls['add'];

        echo '

		<p>' . __("Enter some information about the book that you'd like to add, and I'll try to fetch the information directly from Amazon.", MRLTD) . '</p>';

		if ($options['multiuserMode']) {
		    if ( current_user_can('administrator') ) {
		
		echo '
		<p>' . sprintf(__("My Reading Library is currently set to search the <strong>amazon%s</strong> domain; you can change this setting and others in the <a href='%s'>options page</a>.", MRLTD), $options['domain'], $mrl_url->urls['options']) . '</p>';
			}
		}

		echo '
		<form method="post" action="' . $thispage . '">
		';

        if ( function_exists('wp_nonce_field') )
            wp_nonce_field('my-reading-library-add');

        echo '
			<p><label for="isbn"><acronym title="International Standard Book Number">' . __("ISBN", MRLTD) . '</acronym>:</label><br />
			<input type="text" name="u_isbn" id="isbn" size="25" value="' . $results[0]['asin'] . '" /></p>

			<p><strong>' . __("or", MRLTD) . '</strong></p>

			<p><label for="title">' . __("Title", MRLTD) . ':</label><br />
			<input type="text" name="u_title" id="title" size="50" value="' . $results[0]['title'] . '" /></p>

			<p><label for="title">' . __("Author", MRLTD) . ' (' . __("optional", MRLTD) . '):</label><br />
			<input type="text" name="u_author" id="author" size="50" value="' . $results[0]['author'] . '" /></p>

			<p><input class="button" type="submit" value="' . __("Search", MRLTD) . '" /></p>

		</form>

		</div>

		<div class="mrl-add-grouping">

			<h3>' . __("Add a Book Manually", MRLTD) . '</h3>

			<form method="post" action="' . get_option('siteurl') . '/wp-content/plugins/my-reading-library/admin/function-add.php">

			';

        if ( function_exists('wp_nonce_field') )
            wp_nonce_field('my-reading-library-manual-add');

        echo '
				<p><label for="custom_title">' . __("Title", MRLTD) . ':</label><br />
				<input type="text" name="custom_title" id="custom_title" size="50" /></p>

				<p><label for="custom_author">' . __("Author", MRLTD) . ':</label><br />
				<input type="text" name="custom_author" id="custom_author" size="50" /></p>

				<p><label for="custom_image">' . __("Link to image", MRLTD) . ':</label><br />
				<small>' . __("Remember, leeching images from other people's servers is improper. Upload your own images or use Amazon's.", MRLTD) . '</small><br />
				<input type="text" name="custom_image" id="custom_image" size="50" /></p>

				<p><input class="button" type="submit" value="' . __("Add Book", MRLTD) . '" /></p>

			</form>

			</div>

		</div>
		';

    }
}

?>
