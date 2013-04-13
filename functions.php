<?php

# The mini-lib
require_once get_template_directory() . '/p/krr.php';


# Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 750;


# Setup
if ( !function_exists('polos_setup') ) {
	function polos_setup() {
		$theme_dir = get_template_directory();

		# i18n
		load_theme_textdomain( 'polos', "{$theme_dir}/l" );

		# Menus
		register_nav_menus( array('main'=> __('Main Menu', 'polos')) );

		# Dropdwon menu support for small screen devices
		if ( !function_exists('kc_dropdown_menu') && !is_admin() )
			require_once "{$theme_dir}/p/kc-dropdown-menu.php";

		# Features
		add_theme_support( 'custom-background', array(
			'default-image' => get_template_directory_uri() . '/i/bg-dark.png',
			'default-color' => '151515'
		) );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );

		add_action( 'widgets_init', 'polos_sidebars' );
	}
}
add_action( 'after_setup_theme', 'polos_setup' );


# <head /> stuff
add_action( 'wp_head', 'kct_head_stuff', 0 );


# Scripts n styles
function polos_sns() {
  wp_enqueue_style( 'polos', get_template_directory_uri().'/style.css', false, '0.1' );

  if ( is_singular() && comments_open() && get_option('thread_comments') )
		wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'polos_sns' );


# Sidebars
function polos_sidebars() {
	register_sidebar( array(
		'id'            => 'wa-bottom',
		'name'          => __( 'Bottom Widget Area', 'polos' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}


function polos_entry_data( $post_id ) {
	global $post;
	if ( is_page() || !$post || is_404() )
		return;

	$title = get_the_title('', false);
	$dtag = empty($title) ? 'a' : 'abbr';
	$time = esc_attr( get_the_date(_x('r', 'yearly archives date format', 'polos')) );
	$date = "<{$dtag} class='date' title='{$time}'";
	if ( !$title )
		$date .= " href='".get_permalink()."'";
	$date .= ">".get_the_date()."</{$dtag}>";

	if ( is_multi_author() ) {
		$author = '<a class="the-author" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" title="'.esc_attr(sprintf(__('View all posts by %s', 'polos'), get_the_author())).'">'.get_the_author().'</a>';
		$byline = sprintf( __('Posted by %1$s on %2$s', 'polos'), $author, $date);
	} else {
		$byline = sprintf( __('Posted on %s', 'polos'), $date );
	}

	$out  = '<p class="entry-data">';
	$out .= $byline;
	if ( post_type_supports(get_post_type(), 'comments')
			&& (comments_open() || kct_get_comments_count(get_the_ID(), 'comment'))
			&& !post_password_required() )
		$out .= ' <a href="'.get_comments_link().'">'.__('Comments', 'polos').'</a>';
	if ( $edit_link = get_edit_post_link() )
		$out .= ' <a href="'.$edit_link.'">'.__('Edit', 'polos').'</a>';
	$out .= '</p>';

	echo $out;
}
add_action( 'kct_after_entry_title', 'polos_entry_data' );


# Searchform on #branding
add_action( 'kct_after_branding', 'get_search_form' );


# Replace parent menu item without URL with <span />
function polos_menu_filter( $item_output, $item, $depth, $args ) {
	if ( $item->url == '#parent#' )
		$item_output = '<span class="parent">'.apply_filters( 'the_title', $item->title, $item->ID ).'</span>';

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'polos_menu_filter', 11, 4 );


# Page title
function polos_page_title() {
	$title = $desc = '';

	# Search result page
	if ( is_search() && $title = get_search_query() )
		$title = sprintf(__('Search results for %s', 'polos'), "<span class='q'>{$title}</span>");

	# Categories / terms archive
	elseif ( (is_category() || is_tax()) && $title = single_term_title('', false) )
		$title = sprintf(__('Entries filed under %s', 'polos'), "<span class='q'>{$title}</span>");

	# Tags archive
	elseif ( is_tag() && $title = single_term_title('', false) )
		$title = sprintf(__('Entries tagged %s', 'polos'), "<span class='q'>{$title}</span>");

	# Daily archive
	elseif ( is_day() )
		$title = sprintf(__('Daily archives: %s', 'polos'), get_the_date());

	# Monthly archive
	elseif ( is_month() )
		$title = sprintf(__('Monthly archives: %s', 'polos'), get_the_date(_x('F Y', 'monthly archives date format', 'polos')));

	# Yearly archive
	elseif ( is_year() )
		$title = sprintf(__('Yearly archives: %s', 'polos'), get_the_date(_x('Y', 'yearly archives date format', 'polos')));

	# Author archive
	elseif ( is_author() )
		$title = sprintf(__('Entries posted by %s', 'polos'), get_the_author_meta('display_name', get_query_var('author')));

	# Custom post type archive
	elseif ( is_post_type_archive() ) {
		$object = get_post_type_object( get_query_var('post_type') );
		$title = $object->label;
		$desc = $object->description;
	}

	$title = apply_filters( 'polos_page_title', $title );
	$desc = apply_filters( 'polos_page_desc', $desc );

	if ( !empty($title) ) { ?>
	<header class="page-title">
		<h1><?php echo $title ?></h1>
		<?php if ( !empty($desc) ) echo wpautop( $desc ); ?>
	</header>
	<?php }
}
add_action( 'kct_before_loop', 'polos_page_title' );


# Post meta
function polos_after_singular_content() {
	if ( !is_singular() )
		return;

	wp_link_pages(array(
		'before' => '<nav class="entry-pages"><span class="label">'.__('Pages').':</span>',
		'after'  => '</nav>'
	));

	if ( is_singular('post') )
		kct_post_terms();
}
add_action( 'kct_after_entry_content', 'polos_after_singular_content' );


# Responses list (comments & pings)
function polos_comments_list() {
	if ( is_singular() && post_type_supports(get_post_type(), 'comments') )
		comments_template('', true);
}
add_action( 'kct_after_entry_content', 'polos_comments_list' );


# Comments nav
function polos_comments_nav() {
	if ( get_comment_pages_count() > 1 && get_option('page_comments') ) { ?>
		<nav class="posts-nav">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'polos' ); ?></h1>
			<?php paginate_comments_links( array('type' => 'list') ) ?>
		</nav>
	<?php }
}
add_action( 'kct_after_comment_list', 'polos_comments_nav' );
