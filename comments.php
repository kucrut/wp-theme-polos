<?php
/**
 * @package Baca_Theme
 * @version 0.1
 */
?>
	<div id="comments">
	<?php if ( post_password_required() ) { ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'baca' ); ?></p>
	</div><!-- #comments -->
	<?php return; } ?>

	<?php
		if ( have_comments() ) {
			kct_response_list( get_the_ID() );
		}
		elseif ( !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') ) { ?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'baca' ); ?></p>
	<?php } ?>

	<?php comment_form(); ?>

</div><!-- #comments -->
