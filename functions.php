<?php

/**
 * @package KRR_Theme
 * @version 0.1
 */


/**
 * Check requirements
 *
 * This will check or the required plugins/functions needed
 * for the theme to work. If one of the requirements doesnt exist,
 * it will activate the default theme set by WP_DEFAULT_THEME constant
 *
 * @param array $reqs Array of classes/functions to check
 */
function krr_check_req( $reqs, $message = '' ) {
	foreach ( $reqs as $req ) {
		if ( !class_exists($req) || !function_exists($req) ) {
			$message .= '<br />&laquo; <a href="'.wp_get_referer().'">Go back</a>.';
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			wp_die( $message );
		}
	}
}


# Version
if ( !defined('KRR_VERSION') )
	define( 'KRR_VERSION', '0.1' );

# Initialise
function krr_init() {
	register_nav_menus(array(
		'main' => __( 'Main Menu', 'ne' )
	));

	add_action( 'widgets_init', 'krr_sidebars' );
}
add_action( 'after_setup_theme', 'krr_init' );


# Scripts n styles
function krr_sns() {
  wp_enqueue_style( 'krr', get_template_directory_uri().'/style.css', false, KRR_VERSION );
}
add_action( 'wp_enqueue_scripts', 'krr_sns' );


# Sidebars
function krr_sidebars() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'krr' ),
		'id' => 'primary',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}



function krr_features() {
	add_theme_support( 'post-thumbnails' );
}


function krr_image_sizes() {
	add_image_size( 'pret', 80, 80, true );
}


/*
 * Document title (<title></title>)
 *
 */
function krr_doc_title( $title ) {
	global $page, $paged;

	$sep = apply_filters( 'kc_doc_title_sep', '&laquo;' );
	$seplocation = apply_filters( 'kc_doc_title_seplocation', 'right' );
	$pg_sep = apply_filters( 'kc_doc_title_pagenum_sep', '|' );
	$home_sep = apply_filters( 'kc_doc_title_home_sep', '&mdash;' );

	$site_name = get_bloginfo( 'name', 'display' );
	$site_desc = get_bloginfo( 'description', 'display');
	$page_num = ( $paged >= 2 || $page >= 2 ) ? " ${pg_sep} " . sprintf( __('Page %s', 'lasplash'), max($paged, $page) ) : '';

	# Homepage
	if ( is_home() || is_front_page() ) {
		$title = $site_name;
		if ( $site_desc )
			$title .= " ${home_sep} ${site_desc}";
		$title .= $page_num;
	} else {
		if ( $seplocation == 'right' )
			$title = "${title} ${page_num} ${sep} ${site_name}";
		else
			$title = "${site_name} ${sep} ${title} ${page_num}";
	}

	return $title;
}
add_filter( 'wp_title', 'krr_doc_title' );


function krr_dev() {
	echo "<div class='kc-dev'><pre>\n";


	echo "</pre></div>\n";
}
//add_action( 'wp_footer', 'krr_dev' );



#require_once dirname(__FILE__).'/sample/__theme_settings.php';
#require_once dirname(__FILE__).'/sample/__plugin_settings.php';
#require_once dirname(__FILE__).'/sample/__post_settings.php';
#require_once dirname(__FILE__).'/sample/__post_settings2.php';
#require_once dirname(__FILE__).'/sample/__user_settings.php';
#require_once dirname(__FILE__).'/sample/__term_settings.php';

/*
add_rewrite_tag( '%gallery%', '([^/]+)' );
add_rewrite_rule( '^gallery/([^/]+)?$', 'index.php?name=$matches[1]&gallery=1', 'top' );
add_action( 'template_redirect', 'gallery_template' );
function gallery_template() {
	global $wp_query;
	if ( isset($wp_query->query_vars['gallery']) && $wp_query->query_vars['gallery'] ) {
		include get_stylesheet_directory() . '/gallery.php';
		exit;
	}
}
*/



?>
