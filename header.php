<?php

/**
 * @package Baca_Theme
 * @version 0.1
 */

ob_start();
language_attributes();
$lang_attr = ob_get_clean();

?>


<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php echo $lang_attr; ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php echo $lang_attr; ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php echo $lang_attr; ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php echo $lang_attr; ?>> <!--<![endif]-->
<head>
  <meta charset="<?php bloginfo( 'charset' ) ?>">
  <title><?php wp_title('') ?></title>
  <?php wp_head() ?>
</head>

<body <?php body_class() ?>>
	<div id="page">
		<header id="branding" role="banner">
			<?php do_action( 'branding_before' ) ?>
			<hgroup>
				<h1 id="site-title"><a class="no-ajaxy" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php if ( $site_desc = get_bloginfo('description') ) { ?>
				<h2 id="site-description"><?php echo $site_desc ?></h2>
				<?php } ?>
			</hgroup>
			<?php wp_nav_menu( array(
				'theme_location'	=> 'main',
				'container'				=> 'nav',
				'container_id'		=> 'main-menu',
				'container_class'	=> 'menu-container main-menu-container',
				'menu_class'			=> 'menu main-menu',
				'fallback_cb'			=> false
			) ); ?>
			<?php do_action( 'branding_after' ) ?>
		</header>
