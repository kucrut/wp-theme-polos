<?php

/**
 * @package Polos_Theme
 * @version 0.3
 */

?>
		<footer id="colophon">
		<?php if ( is_active_sidebar( 'wa-bottom') ) { ?>
			<?php do_action( 'kct_before_sidebar_wa-bottom' ); ?>
			<?php dynamic_sidebar( 'wa-bottom' ); ?>
			<?php do_action( 'kct_after_sidebar_wa-bottom' ); ?>
		<?php
			}
			else {
			$t_copy = sprintf( __('CopyLeft &copy; %d %s', 'polos'), date('Y'), get_bloginfo('name', 'display') );
			$t_credits = sprintf( __('Proudly powered by %s', 'polos'), '<a href="http://wordpress.org">WordPress</a><sup>&reg;</sup>');
		?>
			<p class="nw cl"><?php echo apply_filters( 'kct_text_copyright', $t_copy ) ?></p>
			<p class="nw wp"><?php echo apply_filters( 'kct_text_poweredby', $t_credits ) ?></p>
		<?php } ?>
		</footer>
	</div>
	<?php wp_footer() ?>
</body>
</html>
