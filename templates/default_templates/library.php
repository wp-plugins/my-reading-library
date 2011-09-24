<?php get_header(); ?>
<div class="content">
	<div id="content" class="my-reading-library primary narrowcolumn">
	<div class="post">
		<h2 class="entry-title"><?php _e('My Library', MRLTD) ?></h2>
		<?php if( can_my_reading_library_admin() ) : ?>
		<br><?php _e('Library Admin: ', MRLTD) ?><a href="<?php manage_library_url() ?>"><?php _e('Manage Books', MRLTD) ?></a><br>
		<?php endif; ?>
		<?php print_book_stats() ?>
		<h3><b><u><?php _e('Current Books ', MRLTD) ?>(<?php echo total_books('reading', 0) ?>):</u></b></h3>
		<?php if( have_books('status=reading&orderby=desc&num=-1') ) : ?>
			<ol>
			<?php while( have_books('status=reading&orderby=desc&num=-1') ) : the_book(); ?>
			<li><a href="<?php book_permalink() ?>"><?php book_title() ?></a><?php _e(' by ', MRLTD) ?><?php book_author() ?></li>
			<?php endwhile; ?>
			</ol>
		<?php else : ?>
			<p><?php _e('None', MRLTD) ?></p>
		<?php endif; ?>

		<h3><b><u><?php _e('Planned Books ', MRLTD) ?>(<?php echo total_books('unread', 0) ?>):</u></b></h3>
		<?php if( have_books('status=unread&orderby=desc&num=-1') ) : ?>
			<ol>
			<?php while( have_books('status=unread&orderby=desc&num=-1') ) : the_book(); ?>
			<li><a href="<?php book_permalink() ?>"><?php book_title() ?></a><?php _e(' by ', MRLTD) ?><?php book_author() ?></li>
			<?php endwhile; ?>
			</ol>
		<?php else : ?>
			<p><?php _e('None', MRLTD) ?></p>
		<?php endif; ?>

		<h3><b><u><?php _e('Completed Books ', MRLTD) ?>(<?php echo total_books('read', 0) ?>):</u></b></h3>
		<?php if( have_books('status=read&orderby=finished&order=desc&num=-1') ) : ?>
			<ol>
			<?php while( have_books('status=read&orderby=finished&order=desc&num=-1') ) : the_book(); ?>
			<li><a href="<?php book_permalink() ?>"><?php book_title() ?></a><?php _e(' by ', MRLTD) ?><?php book_author() ?></li>
			<?php endwhile; ?>
			</ol>
		<?php else : ?>
			<p><?php _e('None', MRLTD) ?></p>
		<?php endif; ?>

		<h3><b><u><?php _e('Books on Hold ', MRLTD) ?>(<?php echo total_books('onhold', 0) ?>):</u></b></h3>
		<?php if( have_books('status=onhold&orderby=desc&num=-1') ) : ?>
			<ol>
			<?php while( have_books('status=onhold&orderby=desc&num=-1') ) : the_book(); ?>
			<li><a href="<?php book_permalink() ?>"><?php book_title() ?></a><?php _e(' by ', MRLTD) ?><?php book_author() ?></li>
			<?php endwhile; ?>
			</ol>
		<?php else : ?>
			<p><?php _e('None', MRLTD) ?></p>
		<?php endif; ?>
	</div>
	</div>
</div><!-- #content -->
    <?php weaver_put_wvr_widgetarea('bottom-widget-area','ttw-bot-widget','ttw_hide_widg_posts'); ?>
	<?php weaver_put_wvr_widgetarea('sitewide-bottom-widget-area','ttw-site-bot-widget'); ?>
</div><!-- #container -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>