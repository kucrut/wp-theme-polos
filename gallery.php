<?php get_header() ?>

    <div id="content" role="main">
    <?php
      if ( have_posts() ) {
        while ( have_posts() ) {
          the_post();
          $images = get_posts(array(
						'post_status'	=> 'inherit',
						'post_type'		=> 'attachment',
						'post_parent'	=> get_the_ID()
          ));

          if ( !empty($images) ) {
						echo "<pre>\n";
						foreach ( $images as $img ) {
							print_r( $img );
						}
						echo "</pre>\n";
						echo get_the_ID();
					}
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
