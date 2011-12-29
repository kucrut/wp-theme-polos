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
		<?php } else { ?>
			<p class="nw cl">CopyLeft &copy; <?php echo date('Y') . ' ' . get_bloginfo( 'name', 'display' ) ?></p>
			<p class="nw wp">Proudly powered by <a href="http://wordpress.org">WordPress</a><sup>&reg;</sup></p>
		<?php } ?>
		</footer>
	</div>
	<?php wp_footer() ?>
</body>
</html>
