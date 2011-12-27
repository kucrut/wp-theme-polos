<?php

/**
 * @package Baca_Theme
 * @version 0.1
 */


require_once get_template_directory() . '/p/krr.php';


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 750;


# Setup
if ( !function_exists('baca_setup') ) {
	function baca_setup() {
		register_nav_menus(array(
			'main'		=> __( 'Main Menu', 'baca' )
		));

		# Features
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );

		add_action( 'widgets_init', 'baca_sidebars' );
	}
}
add_action( 'after_setup_theme', 'baca_setup' );


# <head /> stuff
add_action( 'wp_head', 'kct_head_stuff', 0 );


# Scripts n styles
function baca_sns() {
  wp_enqueue_style( 'baca', get_template_directory_uri().'/style.css', false, '0.1' );

  if ( is_singular() && comments_open() && get_option('thread_comments') )
		wp_enqueue_script( 'comment-reply' );
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
add_action( 'kct_after_entry_title', 'after_entry_title_single' );


# Replace parent menu item without URL with <span />
function baca_menu_filter( $item_output, $item, $depth, $args ) {
	if ( isset($item->url) && $item->url == '#parent#' )
		$item_output = str_replace( array('<a href="#parent#">', '</a>'), array('<span class="parent">', '</span>'), $item_output );

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'baca_menu_filter', 11, 4 );


/* Post meta */
function baca_after_singular_content() {
	if ( !is_singular() )
		return;

	wp_link_pages(array(
		'before'	=> '<nav class="entry-pages"><span class="label">'.__('Pages').':</span>',
		'after'		=> '</nav>'
	));

	if ( is_singular('post') )
		kct_post_terms();
}
add_action( 'kct_after_entry_content', 'baca_after_singular_content' );


function baca_comments_list() {
	if ( !is_singular() || (!get_comments_number() && !comments_open()) )
		return;

	comments_template('', true);
}
add_action( 'kct_after_entry_content', 'baca_comments_list' );


?>
