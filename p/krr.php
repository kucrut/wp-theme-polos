<?php

/**
 * @package Polos_Theme
 * @version 0.4
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
function kct_check_req( $reqs, $message = '' ) {
	foreach ( $reqs as $req ) {
		if ( !class_exists($req) || !function_exists($req) ) {
			$message .= '<br />&laquo; <a href="'.wp_get_referer().'">'.__('Go back', 'polos').'</a>.';
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			wp_die( $message );
		}
	}
}


/**
 * Some more body classes
 */
function kct_body_class( $classes ) {
	if ( is_singular() )
		$classes[] = 'singular';

	global $wp_registered_sidebars;
	if ( !empty($wp_registered_sidebars) ) {
		foreach ( array_keys($wp_registered_sidebars) as $sidebar )
			if ( is_active_sidebar($sidebar) )
				$classes[] = "active-sidebar-{$sidebar}";
	}

	return $classes;
}
add_filter( 'body_class', 'kct_body_class' );


/**
 * Print sidebar
 */
function kct_do_sidebar( $sidebar ) {
	if ( !is_active_sidebar($sidebar) ) return; ?>

<?php do_action( "kct_before_sidebar_{$sidebar}" ); ?>
<?php dynamic_sidebar( $sidebar ); ?>
<?php do_action( "kct_after_sidebar_{$sidebar}" ); ?>

<?php }


/**
 * Some more body classes
 */
function kct_post_class( $classes, $class, $post_id ) {
	if ( current_theme_supports('post-thumbnails') && has_post_thumbnail() )
		$classes[] = 'has-post-thumbnail';

	return $classes;
}
add_filter( 'post_class', 'kct_post_class', 10, 3 );


/**
 * Document title (<title></title>)
 */
function kct_doc_title( $title ) {
	global $page, $paged;

	$sep = apply_filters( 'kct_doc_title_sep', '&laquo;' );
	$seplocation = apply_filters( 'kct_doc_title_seplocation', 'right' );
	$pg_sep = apply_filters( 'kct_doc_title_pagenum_sep', '|' );
	$home_sep = apply_filters( 'kct_doc_title_home_sep', '&mdash;' );

	$site_name = get_bloginfo( 'name', 'display' );
	$site_desc = get_bloginfo( 'description', 'display');
	$page_num = ( $paged >= 2 || $page >= 2 ) ? " ${pg_sep} " . sprintf( __('Page %s', 'polos'), max($paged, $page) ) : '';

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
if ( !defined('WPSEO_VERSION') )
	add_filter( 'wp_title', 'kct_doc_title' );


# <head /> stuff
function kct_head_stuff() { ?>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php }


/**
 * Paginate Links on index pages
 */
function kct_paginate_links( $query = null, $echo = true ) {
	if ( !$query ) {
		global $wp_query;
		$query = $wp_query;
	}

	if ( !is_object($query) )
		return false;

	$current = max( 1, $query->query_vars['paged'] );
	$big = 999999999;

	$pagination = array(
		'base'		=> str_replace( $big, '%#%', get_pagenum_link($big) ),
		'format'	=> '',
		'total'		=> $query->max_num_pages,
		'current'	=> $current,
		'type'		=> 'list'
	);
	$links = paginate_links($pagination);

	if ( empty($links) )
		return false;

	if ( $echo )
		echo "<nav class='posts-nav'>\n\t{$links}</nav>\n";
	else
		return $links;
}


/**
 * Get post terms
 *
 * @param $post_object object Post object, either from global $post variable or using the get_post() function
 * @param $echo bool
 * @return array Post terms
 *
 */

function kct_post_terms( $post_object = '', $echo = true ) {
	if ( is_404() )
		return;

	if ( !$post_object ) {
		global $post;
		$post_object = $post;
	}

	if ( !is_object($post_object) )
		return false;

	$terms = array();
	$taxonomies = get_object_taxonomies( $post_object->post_type, 'objects' );

	if ( !is_array($taxonomies) || empty($taxonomies) )
		return false;

	foreach ( $taxonomies as $taxonomy ) {
		if ( !$taxonomy->public )
			continue;

		$label = apply_filters( "kct_post_terms_tax_label_{$taxonomy->name}", $taxonomy->label );
		if ( $post_tems = get_the_term_list($post_object->ID, $taxonomy->name, '', ', ') )
			$terms[$taxonomy->name] = array('label' => $label , 'terms' => $post_tems);
	}

	$terms = apply_filters( 'kct_post_meta', $terms );
	if ( !$echo )
		return $terms;

	$out  = "<ul class='entry-terms'>\n";
	foreach ( $terms as $tax => $tax_terms )
		$out .= "\t<li class='{$tax}'><span class='label'>{$tax_terms['label']}:</span> {$tax_terms['terms']}</li>";
	$out .="</ul>\n";

	echo $out;
}


/**
 * Get comments number of a post
 *
 * @param $post_id int Post ID
 * @param $type Comments type. ''|pings|comment|pingback|trackback Empty string for all types (default)
 *
 * @return int Comments number
 */
function kct_get_comments_count( $post_id = 0, $type = '' ) {
	return count(get_comments(array(
		'post_id' => $post_id,
		'status'  => 'approve',
		'type'    => $type
	)));
}


/**
 * Response list (comments & pings)
 */
function kct_response_list( $post_id = 0 ) {
	foreach ( array('comment' => __('Comments', 'polos'), 'pings' => __('Pings', 'polos')) as $type => $title ) {
		if ( !kct_get_comments_count($post_id, $type) )
			continue; ?>
	<h2 id="<?php echo $type ?>-title"><?php echo apply_filters( "kct_{$type}_list_title", $title, $post_id ) ?></h2>
	<?php do_action( "kct_before_{$type}_list" ) ?>

	<ol id="<?php echo $type ?>list" class="responselist">
		<?php wp_list_comments( array('callback' => "kct_{$type}_list", 'type' => $type) ); ?>
	</ol>

	<?php do_action( "kct_after_{$type}_list" ) ?>

	<?php }
}


/**
 * Comments list
 */
function kct_comment_list( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment-item">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 48 ); ?>
					<cite class="fn"><?php comment_author_link() ?></cite>
				</div>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="comment-date"><?php printf( __( '%1$s at %2$s', 'polos' ), get_comment_date(), get_comment_time() ); ?></a>
					<?php comment_reply_link( array_merge($args, array(
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'before'    => '<span class="reply-link"> &ndash; ',
						'after'     => '</span>'
					)) ); ?>
					<?php edit_comment_link( __('Edit', 'polos'), ' &ndash; ' ); ?>
				</div>
			</footer>

			<div class="comment-content">
				<?php
					if ( $comment->comment_approved == '0' )
						echo '<p><em>'.__( 'Your comment is awaiting moderation.', 'polos' ).'</em></p>';
					comment_text();
				?>
			</div>
		</article>
	<?php
}


/**
 * Pings list
 */
function kct_pings_list( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?><?php edit_comment_link( __('Edit', 'polos'), ' | ' ); ?>
<?php }


/**
 * Comment form fields
 */
function kct_comment_form_fields( $fields ) {
	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = ! empty( $user->ID ) ? $user->display_name : '';

	$req = get_option( 'require_name_email' );
	$aria_req = ($req ? " aria-required='true'" : '');

	$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . __('Name', 'polos') . ($req ? ' <span class="required">*</span>' : '')  . '</label>'.
                      '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['email']  = '<p class="comment-form-email"><label for="email">' . __('Email', 'polos') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
                      '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
	$fields['url']    = '<p class="comment-form-url"><label for="url">' . __('Website', 'polos') . '</label>' .
                      '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>';

	return $fields;
}
add_filter( 'comment_form_default_fields', 'kct_comment_form_fields' );
