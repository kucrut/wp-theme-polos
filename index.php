<?php

/**
 * @package Polos_Theme
 * @version 0.4
 */

?>
<?php get_header() ?>

		<div id="content" role="main">
		<?php do_action( 'kct_before_loop' ) ?>
		<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'content', apply_filters('kct_content_template', get_post_type()) );
				}

				kct_paginate_links();
			}
			else {
				get_template_part( 'content', apply_filters('kct_content_template', '404') );
			}
		?>
		<?php do_action( 'kct_after_loop' ) ?>
		</div>

<?php get_footer() ?>
