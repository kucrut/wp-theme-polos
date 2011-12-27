<?php

/**
 * @package Baca_Theme
 * @version 0.1
 */

?>
				<?php do_action( 'kct_before_entry' ); ?>
				<article id="not-found" class="hentry 404">
					<header class="entry-title">
						<h1><?php _e('Not found', 'baca') ?></h1>
						<?php do_action( 'kct_after_entry_title' ); ?>
					</header>

					<?php do_action( 'kct_before_entry_content' ); ?>
					<div class="entry-content">
            <p><?php _e('Ooops, Not found!', 'baca') ?></p>
					</div>
					<?php do_action( 'kct_after_entry_content' ); ?>

				</article>
				<?php do_action( 'kct_after_entry' ); ?>
