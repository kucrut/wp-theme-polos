<?php

/**
 * @package Polos_Theme
 * @version 0.2
 */

?>
				<?php do_action( 'kct_before_entry' ); ?>
				<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
					<header class="entry-title">
						<?php if ( $title = get_the_title() ) { ?>
						<h1><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h1>
						<?php } ?>
						<?php do_action( 'kct_after_entry_title' ); ?>
					</header>

					<?php do_action( 'kct_before_entry_content' ); ?>
					<div class="entry-content">
						<?php the_content(__('Continue&hellip;', 'polos')) ?>
					</div>
					<?php do_action( 'kct_after_entry_content' ); ?>

				</article>
				<?php do_action( 'kct_after_entry' ); ?>
