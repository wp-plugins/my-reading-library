<?php
    global $wpdb, $mrl_domains;
    $options = get_option('MyReadingLibraryOptions');
?>
<div class="my-reading-library">
	<br>
	<?php if (!$options['hideCurrentBooks']) { ?>
	<h4><u><strong><?php _e('Current Books:', MRLTD) ?></strong></u></h4>
	<?php if( have_books('status=reading&orderby=desc') ) : ?>
		<?php while( have_books('status=reading&orderby=desc') ) : the_book(); ?>
			<table style="font-size:9px;"><tr><td style="vertical-align:top;"><a target="<?php book_target() ?>" href="<?php book_url() ?>"><img src="<?php book_image() ?>" alt="<?php book_title() ?>" /></a></td><td style="vertical-align:top;"><a href="<?php book_permalink() ?>"><strong><?php book_title() ?></strong></a><br><?php _e('By ', MRLTD) ?><?php book_author() ?><?php if( !is_custom_book() ): ?><br><a target="_blank" href="<?php book_url() ?>#customerReviews"><?php _e('Amazon Customer Reviews', MRLTD) ?></a><?php endif; ?><br><?php pages_read() ?></td></tr></table>
		<?php endwhile; ?>
	<?php else : ?>
		<?php _e('None', MRLTD) ?><br><br>
	<?php endif; ?>
	<?php } ?>
	<?php if (!$options['hidePlannedBooks']) { ?>
	<h4><u><strong><?php _e('Planned Books:', MRLTD) ?></strong></u></h4>
	<?php if( have_books('status=unread&orderby=desc') ) : ?>
		<?php while( have_books('status=unread&orderby=desc') ) : the_book(); ?>
			<table style="font-size:9px;"><tr><td style="vertical-align:top;"><a target="<?php book_target() ?>" href="<?php book_url() ?>"><img src="<?php book_image() ?>" alt="<?php book_title() ?>" /></a></td><td style="vertical-align:top;"><a href="<?php book_permalink() ?>"><strong><?php book_title() ?></strong></a><br><?php _e('By ', MRLTD) ?><?php book_author() ?><?php if( !is_custom_book() ): ?><br><a target="_blank" href="<?php book_url() ?>#customerReviews"><?php _e('Amazon Customer Reviews', MRLTD) ?></a><?php endif; ?></td></tr></table>
		<?php endwhile; ?>
	<?php else : ?>
		<?php _e('None', MRLTD) ?><br><br>
	<?php endif; ?>
	<?php } ?>
	<?php if (!$options['hideFinishedBooks']) { ?>
	<h4><u><strong><?php _e('Completed Books:', MRLTD) ?></strong></u></h4>
	<?php if( have_books('status=read&orderby=finished&order=desc') ) : ?>
		<?php while( have_books('status=read&orderby=finished&order=desc') ) : the_book(); ?>
			<table style="font-size:9px;"><tr><td style="vertical-align:top;"><a target="<?php book_target() ?>" href="<?php book_url() ?>"><img src="<?php book_image() ?>" alt="<?php book_title() ?>" /></a></td><td style="vertical-align:top;"><a href="<?php book_permalink() ?>"><strong><?php book_title() ?></strong></a><br><?php _e('By ', MRLTD) ?><?php book_author() ?><?php if( !is_custom_book() ): ?><br><a target="_blank" href="<?php book_url() ?>#customerReviews"><?php _e('Amazon Customer Reviews', MRLTD) ?></a><?php endif; ?><br><strong><?php _e('My Rating:', MRLTD) ?></strong> <?php book_rating() ?> <?php book_review_link() ?></td></tr></table>
		<?php endwhile; ?>
	<?php else : ?>
		<?php _e('None', MRLTD) ?><br><br>
	<?php endif; ?>
	<?php } ?>
	<?php if (!$options['hideBooksonHold']) { ?>
	<h4><u><strong><?php _e('Books on Hold:', MRLTD) ?></strong></u></h4>
	<?php if( have_books('status=onhold&orderby=desc') ) : ?>
		<?php while( have_books('status=onhold&orderby=desc') ) : the_book(); ?>
			<table style="font-size:9px;"><tr><td style="vertical-align:top;"><a target="<?php book_target() ?>" href="<?php book_url() ?>"><img src="<?php book_image() ?>" alt="<?php book_title() ?>" /></a></td><td style="vertical-align:top;"><a href="<?php book_permalink() ?>"><strong><?php book_title() ?></strong></a><br><?php _e('By ', MRLTD) ?><?php book_author() ?><?php if( !is_custom_book() ): ?><br><a target="_blank" href="<?php book_url() ?>#customerReviews"><?php _e('Amazon Customer Reviews', MRLTD) ?></a><?php endif; ?><br><?php pages_read() ?></td></tr></table>
		<?php endwhile; ?>
	<?php else : ?>
		<?php _e('None', MRLTD) ?><br><br>
	<?php endif; ?>
	<?php } ?>
	<?php if (!$options['hideViewLibrary']) { ?>
	<form>
	<input style="font-size:12px;" type="button" value="<?php _e("Complete Library", MRLTD) ?>" onclick="window.location.href='<?php library_url() ?>'">
<!--	<a href="<?php library_url() ?>"><?php _e('View Complete Library', MRLTD) ?></a> --><!-- alternate text version -->	
	</form>
	<?php } ?>
</div>