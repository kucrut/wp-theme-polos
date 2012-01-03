<?php

/**
 * @package Polos_Theme
 * @version 0.3
 */

?>
				<?php do_action( 'kct_before_entry' ); ?>
				<article id="post-<?php the_ID() ?>" <?php post_class() ?>>
					<header class="entry-title">
						<?php
							if ( $title = get_the_title() ) {
								$title = is_singular() ? "<h1>{$title}</h1>\n" : "<h1><a href='".get_permalink()."' title='".the_title_attribute(array('echo' => false))."'>{$title}</a></h1>\n";
								echo apply_filters( 'kct_entry_title', $title );
							}
						?>
						<?php do_action( 'kct_after_entry_title' ); ?>
					</header>

					<?php do_action( 'kct_before_entry_content' ); ?>
					<div class="entry-content">
						<?php the_content(__('Continue&hellip;', 'polos')) ?>
					</div>
					<?php do_action( 'kct_after_entry_content' ); ?>

				</article>
				<?php do_action( 'kct_after_entry' ); ?>
