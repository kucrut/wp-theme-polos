<?php

/**
 * @package Baca_Theme
 * @version 0.1
 */


require_once get_template_directory() . '/p/krr.php';


# Initialise
function baca_init() {
	register_nav_menus(array(
		'main'		=> __( 'Main Menu', 'baca' )
	));

	add_action( 'widgets_init', 'baca_sidebars' );
}
add_action( 'after_setup_theme', 'baca_init' );


# Scripts n styles
function baca_sns() {
  wp_enqueue_style( 'baca', get_template_directory_uri().'/style.css', false, '0.1' );
}
add_action( 'wp_enqueue_scripts', 'baca_sns' );


# Sidebars
function baca_sidebars() {
	register_sidebar( array(
		'id'						=> 'wa-bottom',
		'name'					=> __( 'Bottom Widget Area', 'baca' ),
		'before_widget'	=> '<aside id="%1$s" class="widget %2$s">',
		'after_widget'	=> "</aside>",
		'before_title'	=> '<h3 class="widget-title">',
		'after_title'		=> '</h3>',
	) );
}



function baca_features() {
	add_theme_support( 'post-thumbnails' );
}


function baca_dev() {
	echo "<div class='kc-dev'><pre>\n";


	echo "</pre></div>\n";
}
//add_action( 'wp_footer', 'baca_dev' );



function after_entry_title_single( $post_id ) {
	if ( is_404() || is_page() )
		return;

	$out  = '<p class="entry-data">';
	$out .= '<span class="date" title="'.sprintf(__('Posted on %1$s', 'baca'), esc_attr(get_the_date('r')) ).'">'.get_the_date().'</span>';
	if ( comments_open() && ! post_password_required() )
		$out .= ' <a href="'.get_comments_link().'">'.__('Comments', 'baca').'</a>';
	if ( $edit_link = get_edit_post_link() )
		$out .= ' <a href="'.$edit_link.'">'.__('Edit', 'baca').'</a>';
	$out .= '</p>';

	echo $out;
}
add_action( 'baca_after_entry_title', 'after_entry_title_single' );


# Replace parent menu item without URL with <span />
function baca_menu_filter( $item_output, $item, $depth, $args ) {
	if ( isset($item->url) && $item->url == '#parent#' )
		$item_output = str_replace( array('<a href="#parent#">', '</a>'), array('<span class="parent">', '</span>'), $item_output );

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'baca_menu_filter', 11, 4 );


/* Post meta */
function baca_after_entry_content() {
	if ( !is_singular('post') )
		return;

	$out = '';
	if ( $terms = kc_post_terms() ) {
		foreach ( $terms as $tax => $tax_terms )
			$out .= "<p class='post-{$tax}'>{$tax_terms}</p>\n";
	}

	if ( $out )
		echo "<div class='entry-terms'>\n\t{$out}</div>\n";
}
add_action( 'baca_after_entry_content', 'baca_after_entry_content' );


?>
