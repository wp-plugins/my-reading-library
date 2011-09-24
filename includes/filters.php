<?php
/**
 * The default filters are pretty self-explanatory. Comment them out or remove them with remove_filter() if you don't want them.
 * @package my-reading-library
 */

add_filter('book_title', 'wptexturize');
add_filter('book_author', 'wptexturize');
add_filter('the_book_author', 'ucwords');
add_filter('book_added', 'mrl_format_date');
add_filter('book_started', 'mrl_format_date');
add_filter('book_finished', 'mrl_format_date');

?>
