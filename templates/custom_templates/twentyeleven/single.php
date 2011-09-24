<?php get_header(); ?>
<?php global $mrl_id; ?>
<div id="primary">
<div class="content">
	<div id="content" class="my-reading-library primary narrowcolumn">
	<div class="post">
		<?php if( have_books(intval($mrl_id)) ) : ?>
			<?php while ( have_books(intval(mrl_id)) ) : the_book(); ?>
			<h2 class="entry-title"><?php book_title() ?></h2>
			<?php if( can_my_reading_library_admin() ) : ?>
			<br><?php _e('Library Admin: ', MRLTD) ?><a href="<?php manage_library_url() ?>"><?php _e('Manage Books', MRLTD) ?></a> &raquo; <a href="<?php book_edit_url() ?>"><?php _e('Edit Book', MRLTD) ?></a><br>
			<?php endif; ?>
			<br>
			<table width="100%" style="font-size:14px; border:none;">
			  <tr>
			    <td style="border:none; vertical-align:top;"><a href="<?php book_url() ?>"><img src="<?php book_limage() ?>" alt="<?php book_title() ?>" /></a></td>
				<td style="border:none; vertical-align:top;"><b><?php _e('Title:', MRLTD) ?></b> <?php book_title() ?><br>
				    <b><?php _e('Author:', MRLTD) ?></b> <?php book_author() ?><br>
			<?php if( !is_custom_book() ): ?>
				<b><?php _e('Description:', MRLTD) ?></b> <a target="_blank" href="<?php book_url() ?>"><?php _e('Amazon Detail Page', MRLTD) ?></a><br>
			<?php endif; ?>
			<?php if( !is_custom_book() ): ?>
			<b><?php _e('Reviews:', MRLTD) ?></b> <a target="_blank" href="<?php book_url() ?>#customerReviews"><?php _e('Amazon Customer Reviews', MRLTD) ?></a><br>
			<?php endif; ?>
			<b><?php _e('Progress:', MRLTD) ?></b> <?php pages_read() ?><br>
			<b><?php _e('My Rating:', MRLTD) ?></b> <?php book_rating() ?> <?php book_review_link() ?>
				</td>
			  </tr>
			</table>
			<br>
			<table width="100%" style="font-size:14px; border: 1px solid #e2e2e2;">
			  <tr>
			    <td><b><?php _e('Started:', MRLTD) ?></b> <?php book_started() ?></td>
				<td><b><?php _e('Finished:', MRLTD) ?></b> <?php book_finished() ?></td>
			  </tr>
			</table>
			<br>
			<form>
			<input style="font-size:16px;" type="button" value="<?php _e("Complete Library", MRLTD) ?>" onclick="window.location.href='<?php library_url() ?>'">
			</form>
<!--			<a href="<?php library_url() ?>"><?php _e('Return to My Library', MRLTD) ?></a> --><!-- alternate text version -->
			<?php endwhile; ?>
			<?php else : ?>
			<p><?php _e('That particular book does not exist.', MRLTD) ?></p>
		<?php endif; ?>
	</div>
	</div>
</div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>