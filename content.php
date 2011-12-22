<?php

/**
 * @package KRR_Theme
 * @version 0.1
 */

?>
        <article id="post-<?php the_ID() ?>" <?php post_class() ?>>
          <hgroup>
            <h1 class="entry-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"><?php the_title() ?></a></h1>
          </hgroup>
          <div class="entry-content">
            <?php the_content() ?>
          </div>
        </article>
