<?php

/**
 * @package KRR_Theme
 * @version 0.1
 */

?>
<?php get_header() ?>

    <div id="content" role="main">
    <?php
      if ( have_posts() ) {
        while ( have_posts() ) {
          the_post();
          get_template_part( 'content' );
        }
      } else {
        get_template_part( 'content', '404' );
      }
    ?>
    </div>

    <?php if ( is_active_sidebar( 'primary' ) ) { ?>
    <div id="primary" class="sidebar">
      <?php dynamic_sidebar( 'primary' ) ?>
    </div>
    <?php } ?>


<?php get_footer() ?>
