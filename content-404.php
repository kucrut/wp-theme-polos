<?php

/**
 * @package Baca_Theme
 * @version 0.1
 */

?>
				<?php do_action( 'baca_before_entry' ); ?>
				<article id="not-found" class="hentry 404">
					<hgroup class="entry-title">
						<h1><?php _e('Not found', 'baca') ?></h1>
						<?php do_action( 'baca_after_entry_title' ); ?>
					</hgroup>

					<?php do_action( 'baca_before_entry_content' ); ?>
					<div class="entry-content">
            <p><?php _e('Ooops, Not found!', 'baca') ?></p>
					</div>
					<?php do_action( 'baca_after_entry_content' ); ?>

				</article>
				<?php do_action( 'baca_after_entry' ); ?>
