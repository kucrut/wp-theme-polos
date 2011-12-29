<?php

/**
 * @package Polos_Theme
 * @version 0.3
 */

?>

	<div id="comments">
	<?php if ( post_password_required() ) { ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'polos' ); ?></p>
	</div>
	<?php return; } ?>

	<?php if ( have_comments() ) { ?>
		<?php kct_response_list( get_the_ID() ); ?>

		<?php if ( !comments_open() && !is_page() && post_type_supports(get_post_type(), 'comments') && get_comments_number() ) { ?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'polos' ); ?></p>
		<?php } ?>

	<?php } ?>
	<?php comment_form(); ?>
	</div>
