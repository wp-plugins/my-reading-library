<?php
/**
 * Functions for theming and templating.
 * @package my-reading-library
 */

/**
 * The array index of the current book in the {@link $books} array.
 * @global integer $GLOBALS['current_book']
 * @name $current_book
 */
$current_book = 0;
/**
 * The array of books for the current query.
 * @global array $GLOBALS['books']
 * @name $books
 */
$books = null;
/**
 * The current book in the loop.
 * @global object $GLOBALS['book']
 * @name $book
 */
$book = null;

/**
 * Formats a date according to the date format option.
 * @param string The date to format, in any string recogniseable by strtotime.
 */
function mrl_format_date( $date ) {
    $options = get_option('MyReadingLibraryOptions');
    if ( !is_numeric($date) )
        $date = strtotime($date);
    if ( empty($date) )
        return '';
    return apply_filters('mrl_format_date', date($options['formatDate'], $date));
}

/**
 * Returns true if the date is a valid one; false if it isn't.
 * @param string The date to check.
 */
function mrl_empty_date( $date ) {
    return ( empty($date) || $date == "0000-00-00 00:00:00" );
}

/**
 * Prints the book's title.
 * @param bool $echo Whether or not to echo the results.
 */
function book_title( $echo = true ) {
    global $book;
    $title = stripslashes(apply_filters('book_title', $book->title));
    if ( $echo )
        echo $title;
    return $title;
}

/**
 * Prints the book's reader.
 * @param bool $echo Wether or not to echo the results.
 */
function book_reader( $echo=true ) {
    global $book;

    $user_info = get_userdata($book->reader);

    if ( $echo )
        echo $user_info->display_name;
    return $user_info->display_name;

}

/**
 * Prints the user name
 * @param int $reader_id Wordpress ID of the reader. If 0, prints the current user name.
 */
function print_reader( $echo=true, $reader_id = 0) {
    global $userdata;

    $username='';

    if (!$reader_id) {
        get_currentuserinfo();
        $username = $userdata->user_login;
    } else {
        $user_info = get_userdata($reader_id);
        $username = $user_info->user_login;
    }

    if ($echo)
        echo $username;
    return $username;
}

/**
 * Prints the author of the book.
 * @param bool $echo Whether or not to echo the results.
 */
function book_author( $echo = true ) {
    global $book;
    $author = apply_filters('book_author', $book->author);
    if ( $echo )
        echo $author;
    return $author;
}

/**
 * Prints a URL to the book's Widget image, usually used within an HTML img element.
 * @param bool $echo Whether or not to echo the results.
 */
function book_image( $echo = true ) {
    global $book;
    $image = apply_filters('book_image', $book->image);
    if ( $echo )
        echo $image;
    return $image;
}

// Added Begin
/**
 * Prints a URL to the book's Library image, usually used within an HTML img element.
 * @param bool $echo Whether or not to echo the results.
 */
function book_limage( $echo = true ) {
    global $book;
    $limage = apply_filters('book_limage', $book->limage);
    if ( $echo )
        echo $limage;
    return $limage;
}
// Added End

/**
 * Prints the date when the book was added to the database.
 * @param bool $echo Whether or not to echo the results.
 */
function book_added( $echo = true ) {
    global $book;
    $added = apply_filters('book_added', $book->added);
    if ( $echo )
        echo $added;
    return $added;
}

/**
 * Prints the date when the book's status was changed from unread to reading.
 * @param bool $echo Whether or not to echo the results.
 */
function book_started( $echo = true ) {
    global $book;
    if ( mrl_empty_date($book->started) )
        $started = __('Not Started', MRLTD);
    else
        $started = apply_filters('book_started', $book->started);
    if ( $echo )
        echo $started;
    return $started;

}

/**
 * Prints the date when the book's status was changed from reading to read.
 * @param bool $echo Whether or not to echo the results.
 */
function book_finished( $echo = true ) {
    global $book;
    if ( mrl_empty_date($book->finished) )
        $finished = __('Not Finished', MRLTD);
    else
        $finished = apply_filters('book_finished', $book->finished);
    if ( $echo )
        echo $finished;
    return $finished;
}

/**
 * Prints the current book's status with optional overrides for messages.
 * @param bool $echo Whether or not to echo the results.
 */
function book_status( $echo = true, $unread = '', $reading = '', $read = '', $onhold = '' ) {
    global $book, $mrl_statuses;

    if ( empty($unread) )
        $unread = $mrl_statuses['unread'];
    if ( empty($reading) )
        $reading = $mrl_statuses['reading'];
    if ( empty($read) )
        $read = $mrl_statuses['read'];
    if ( empty($onhold) )
        $onhold = $mrl_statuses['onhold'];

    switch ( $book->status ) {
        case 'unread':
            $text = $unread;
            break;
        case 'onhold':
            $text = $onhold;
            break;
        case 'reading':
            $text = $reading;
            break;
        case 'read':
            $text = $read;
            break;
        default:
            return;
    }

    if ( $echo )
        echo $text;
    return $text;
}

/**
 * Prints the number of books started and finished within a given time period.
 * @param string $interval The time interval, eg  "1 year", "3 month"
 * @param bool $echo Whether or not to echo the results.
 */
function books_read_since( $interval, $echo = true ) {
    global $wpdb;

    $interval = $wpdb->escape($interval);
    $num = $wpdb->get_var("
	SELECT
		COUNT(*) AS count
	FROM
        {$wpdb->prefix}my_reading_library
	WHERE
		DATE_SUB(CURDATE(), INTERVAL $interval) <= b_finished
        ");

    if ( $echo )
//        echo "$num book".($num != 1 ? 's' : '');
        echo "<b>$num</b>";
    return $num;
}

/**
 * Prints book reading statistics.
 * @param string $time_period The period to measure average over, eg "year", "month".
 */
function print_book_stats($time_period = 'year')
{
	echo '<br>' . __("Total books in all categories: ", MRLTD);
	total_books(0);
	echo '<br>' . __("Books read in the last year: ", MRLTD);
	books_read_since('1 year');
	echo '<br>' . __("Books read in the last month: ", MRLTD);
	books_read_since('1 month');
	echo '<br>' . __("Average books read per year: ", MRLTD);
	average_books($time_period, true, false);
}

/**
 * Prints the total number of books in the library.
 * @param string $status A comma-separated list of statuses to include in the count. If ommitted, all statuses will be counted.
 * @param bool $echo Whether or not to echo the results.
 * @param int $userID Counting only userID's books.
 */
function total_books($status = '', $echo = true , $userID = 0) {
    global $wpdb;

	$reader = get_reader_visibility_filter($userID, false);

    if ($status)
	{
        if (strpos($status, ',') === false)
		{
            $status = 'WHERE b_status = "' . $wpdb->escape($status) . '"';
        }
		else
		{
            $statuses = explode(',', $status);

            $status = 'WHERE 1=0';
            foreach ( (array) $statuses as $st )
			{
                $status .= ' OR b_status = "' . $wpdb->escape(trim($st)) . '" ';
            }
        }

		if (!empty($reader))
		{
			$status .= ' AND ' . $reader;
		}
	}
	else
	{
		if (!empty($reader))
		{
			$status = ' WHERE ' . $reader;
		}
    }

    $num = $wpdb->get_var("
	SELECT
		COUNT(*) AS count
	FROM
        {$wpdb->prefix}my_reading_library
        $status
        ");

    if ($echo)
    {
		echo "<b>$num</b>";
	}

    return $num;
}

/**
 * Prints the average number of books read in the given time limit.
 * Unless $absolute is true, the average is computed based on the weighted average of
 * books read witin the last 365 days and those read within the last 30 days.
 * @param string $time_period The period to measure average over, eg "year", "month".
 * @param bool $echo Whether or not to echo the results.
 * @param bool $absolute If true, the average is computed based on the oldest finished date.
 */
function average_books($time_period = 'week', $echo = true, $absolute = true)
{
    global $wpdb;

	if ($absolute)
	{
		$books_per_day = $wpdb->get_var("
		SELECT
			( COUNT(*) / ( TO_DAYS(CURDATE()) - TO_DAYS(MIN(b_finished)) ) ) AS books_per_day_in_year
		FROM
			{$wpdb->prefix}my_reading_library
		WHERE
			b_status = 'read'
		AND b_finished > 0
			");
	}
	else
	{
		$books_per_day_in_year = $wpdb->get_var("
		SELECT
			( COUNT(*) / ( TO_DAYS(CURDATE()) - TO_DAYS(MIN(b_finished)) ) ) AS books_per_day_in_year
		FROM
			{$wpdb->prefix}my_reading_library
		WHERE
			b_status = 'read'
		AND TO_DAYS(b_finished) >= (TO_DAYS(CURDATE()) - 365)
			");

		$books_per_day_in_month = $wpdb->get_var("
		SELECT
			( COUNT(*) / ( TO_DAYS(CURDATE()) - TO_DAYS(MIN(b_finished)) ) ) AS books_per_day_in_month
		FROM
			{$wpdb->prefix}my_reading_library
		WHERE
			b_status = 'read'
		AND TO_DAYS(b_finished) >= (TO_DAYS(CURDATE()) - 30)
			");

		// Give twice the weight for the last month's average than the total of last year's.
		$books_per_day = ((2.0 * $books_per_day_in_month) + $books_per_day_in_year) / 3.0;
	}

    $average = 0;
    switch ( $time_period ) {
        case 'year':
            $average = round($books_per_day * 365);
            break;

        case 'month':
            $average = round($books_per_day * 31);
            break;

        case 'week':
            $average = round($books_per_day * 7);
			break;

        case 'day':
            $average = round($books_per_day * 1);
            break;

        default:
            return 0;
    }

    if($echo)
    {
		if ($absolute)
		{
			$type = __("an absolute", MRLTD);
		}
		else
		{
			$type = __("a current", MRLTD);
		}
		printf(__("<b>%s</b><br><br>", MRLTD), $average);
	}

    return $average;
}

/**
 * Prints the URL to an internal page displaying data about the book.
 * @param bool $echo Whether or not to echo the results.
 * @param int $id The ID of the book to link to. If ommitted, the current book's ID will be used.
 */
function book_permalink( $echo = true, $id = 0 ) {
    global $book, $wpdb;
    $options = get_option('MyReadingLibraryOptions');

    if ( !empty($book) && empty($id) )
        $the_book = $book;
    elseif ( !empty($id) )
        $the_book = get_book(intval($id));

    if ( $the_book->id < 1 )
        return;

    $author = $the_book->nice_author;
    $title = $the_book->nice_title;



    if ( $options['useModRewrite'] )
        $url = get_option('home') . "/" . preg_replace("/^\/|\/+$/", "", $options['permalinkBase'])  . "/$author/$title/";
    else
        $url = get_option('home') . "/index.php?my_reading_library_author=$author&amp;my_reading_library_title=$title";

    $url = apply_filters('book_permalink', $url);
    if ( $echo )
        echo $url;
    return $url;
}


/**
 * Prints the URL to an internal page displaying books by a certain reader.
 * @param bool $echo Wether or not to echo the results.
 * @param int $reader The reader id. If omitted, links to all books.
 */
function book_reader_permalink( $echo = true, $reader = 0) {
    global $book, $wpdb;

    $options = get_option('MyReadingLibraryOptions');

    if ( !$reader )
        $reader = $book->reader;

    if ( !$reader )
        return;

    if ($options['multiuserMode']) {
        $url = get_option('home') . "/" . preg_replace("/^\/|\/+$/", "", $options['permalinkBase']) . "/reader/$reader/";
    } else {
        $url = get_option('home') . "/index.php?my_reading_library_library=1&my_reading_library_reader=$reader";
    }

    if ($echo)
        echo $url;
    return $url;
}

/**
 * Prints a URL to the book's Amazon detail page. If the book is a custom one, it will print a URL to the book's permalink page.
 * @param bool $echo Whether or not to echo the results.
 * @param string $domain The Amazon domain to link to. If ommitted, the default domain will be used.
 * @see book_permalink()
 * @see is_custom_book()
 */
function book_url( $echo = true, $domain = null ) {
    global $book;
    $options = get_option('MyReadingLibraryOptions');

    if ( empty($domain) )
        $domain = $options['domain'];

    if ( is_custom_book() )
        return book_permalink($echo);
    else {
        $url = apply_filters('book_url', "http://www.amazon{$domain}/exec/obidos/ASIN/{$book->asin}/ref=nosim/{$options['associate']}");
        if ( $echo )
            echo $url;
        return $url;
    }
}

// Added Begin
/**
 * Prints the target for the URL to the book's page (Amazon detail page or details page).
 * @param bool $echo Whether or not to echo the results.
 */
function book_target( $echo = true ) {
	global $book;

	if ( is_custom_book() )
		$target_window = "_self";
	else
		$target_window = "_blank";
	
	if ( $echo )
		echo $target_window;
	return $target_window;
}
// Added End

/**
 * Returns true if the current book is linked to a post, false if it isn't.
 */
function book_has_post() {
    global $book;

    return ( $book->post > 0 );
}

/**
 * Returns or prints the permalink of the post linked to the current book.
 * @param bool $echo Whether or not to echo the results.
 */
function book_post_url( $echo = true ) {
    global $book;

    if ( !book_has_post() )
        return;

    $permalink = get_permalink($book->post);

    if ( $echo )
        echo $permalink;
    return $permalink;
}

/**
 * Returns or prints the title of the post linked to the current book.
 * @param bool $echo Whether or not to echo the results.
 */
function book_post_title( $echo = true ) {
    global $book;

    if ( !book_has_post() )
        return;

    $post = get_post($book->post);

    if ( $echo )
        echo $post->post_title;
    return $post->post_title;
}

/**
 * If the current book is linked to a post, prints an HTML link to said post.
 * @param bool $echo Whether or not to echo the results.
 */
function book_post_link( $echo = true ) {
    global $book;

    if ( !book_has_post() )
        return;

    $link = '<a href="' . book_post_url(0) . '">' . book_post_title(0) . '</a>';

    if ( $echo )
        echo $link;
    return $link;
}

// Added Begin
function book_review_link( $echo = true ) {
    global $book;

    if ( !book_has_post() )
      $review_link = apply_filters('book_review_link', __('(No Review)', MRLTD));
	else
      $review_link = '(<a href="' . book_post_url(0) . '">' . __('Review', MRLTD) . '</a>)';

    if ( $echo )
        echo $review_link;
    return $review_link;
}
// Added End

/**
 * If the user has the correct permissions, prints a URL to the Manage -> My Reading Library page of the WP admin.
 * @param bool $echo Whether or not to echo the results.
 */
function manage_library_url( $echo = true ) {
    global $mrl_url;
    if ( can_my_reading_library_admin() )
        echo apply_filters('book_manage_url', $mrl_url->urls['manage']);
}

/**
 * If the user has the correct permissions, prints a URL to the review-writing screen for the current book.
 * @param bool $echo Whether or not to echo the results.
 */
function book_edit_url( $echo = true ) {
    global $book, $mrl_url;
    if ( can_my_reading_library_admin() )
        echo apply_filters('book_edit_url', $mrl_url->urls['manage'] . '&amp;action=editsingle&amp;id=' . $book->id);
}

/**
 * Returns true if the book is a custom one or false if it is one from Amazon.
 */
function is_custom_book() {
    global $book;
    return empty($book->asin);
}

/**
 * Returns true if the user has the correct permissions to view the My Reading Library admin panel.
 */
function can_my_reading_library_admin() {

//depends on multiuser mode
    $options = get_option('MyReadingLibraryOptions');
    $mrl_level = $options['multiuserMode'] ? 'level_2' : 'level_9';

    return current_user_can($mrl_level);
}

/**
 * Returns true if the current book is owned by the current user
 * Meaningful only when a user is logged in.
 * Works for both multi-user and single-user modes.
 */
function is_my_book()
{
    global $book, $userdata;

	if (is_user_logged_in())
	{
        get_currentuserinfo();
        return $book->reader == $userdata->ID;
	}
	else
	{
		return false;
	}
}

/**
 * Prints a URL pointing to the main library page that respects the useModRewrite option.
 * @param bool $echo Whether or not to echo the results.
 */
function library_url( $echo = true ) {
    $options = get_option('MyReadingLibraryOptions');

    if ( $options['useModRewrite'] )
        $url = get_option('home') . "/" . preg_replace("/^\/|\/+$/", "", $options['permalinkBase']);
    else
        $url = get_option('home') . '/index.php?my_reading_library_library=true';

    $url = apply_filters('book_library_url', $url);

    if ( $echo )
        echo $url;
    return $url;
}

// Added Begins
/**
 * Prints the reader's progress (xx of xxx pages read).
 * @param bool $echo Whether or not to echo the results.
 */
function pages_read( $echo = true ) {
    global $book;
	if ( $book->cpages == 0 )
	  $pages_completed = apply_filters('pages_read', __('Planned Book', MRLTD));
	elseif ( $book->cpages == $book->tpages )
	  $pages_completed = apply_filters('pages_read', __('Completed Book', MRLTD));
    else
      $pages_completed = apply_filters('pages_read', $book->cpages . __(' of ', MRLTD) . $book->tpages . __(' Pages Read', MRLTD));

    if ( $echo )
        echo $pages_completed;
	return $pages_completed;
}
// Added Ends

/**
 * Prints the book's rating or "Unrated" if the book is unrated.
 * @param bool $echo Whether or not to echo the results.
 */
function book_rating( $echo = true ) {
    global $book;
    if ( $book->rating )
        $rate = apply_filters('book_rating', $book->rating . ' of 10');
    else
        $rate = apply_filters('book_rating', __('Unrated', MRLTD));

    if ( $echo )
        echo $rate;
	return $rate;
}

/**
 * Returns a URL to the permalink for a given (custom) page.
 * @param string $page Page name (e.g. custom.php) to create URL for.
 * @param bool $echo Whether or not to echo the results.
 */
function library_page_url( $page, $echo = true ) {
    $options = get_option('MyReadingLibraryOptions');

    if ( $options['useModRewrite'] )
        $url = get_option('home') . "/" . preg_replace("/^\/|\/+$/", "", $options['permalinkBase']) . "/page/" . urlencode($page);
    else
        $url = get_option('home') . '/index.php?my_reading_library_page=' . urlencode($page);

    $url = apply_filters('library_page_url', $url);

    if ( $echo )
        echo $url;
    return $url;
}

/**
 * Returns or prints the currently viewed author.
 * @param bool $echo Whether or not to echo the results.
 */
function the_book_author( $echo = true ) {
    $author = htmlentities(stripslashes($GLOBALS['mrl_author']));
    $author = apply_filters('the_book_author', $author);
    if ( $echo )
        echo $author;
    return $author;
}

/**
 * Use in the main template loop; if un-fetched, fetches books for given $query and returns true whilst there are still books to loop through.
 * @param string $query The query string to pass to get_books()
 * @return boolean True if there are still books to loop through, false at end of loop.
 */
function have_books( $query ) {
    global $books, $current_book;
    if ( !$books ) {
        if ( is_numeric($query) )
            $GLOBALS['books'] = get_book($query);
        else
            $GLOBALS['books'] = get_books($query);
    }
    if (is_a($books, 'stdClass'))
        $books = array($books);
    $have_books = ( !empty($books[$current_book]) );
    if ( !$have_books ) {
        $GLOBALS['books']			= null;
        $GLOBALS['current_book']	= 0;
    }
    return $have_books;
}

/**
 * Advances counter used by have_books(), and sets the global variable $book used by the template functions. Be sure to call it each template loop to avoid infinite loops.
 */
function the_book() {
    global $books, $current_book;
    $GLOBALS['book'] = $books[$current_book];
    $GLOBALS['current_book']++;
}

?>