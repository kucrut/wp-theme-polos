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
function kc_check_req( $reqs, $message = '' ) {
	foreach ( $reqs as $req ) {
		if ( !class_exists($req) || !function_exists($req) ) {
			$message .= '<br />&laquo; <a href="'.wp_get_referer().'">Go back</a>.';
			switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
			wp_die( $message );
		}
	}
}


/*
 * Document title (<title></title>)
 *
 */
function kc_doc_title( $title ) {
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
add_filter( 'wp_title', 'kc_doc_title' );


/**
 * Paginate Links on index pages
 */
function kc_paginate_links( $query = null, $echo = true ) {
	global $wp_rewrite;
	if ( !$query ) {
		global $wp_query;
		$query = $wp_query;
	}

	if ( !is_object($query) )
		return false;

	$current = max( 1, $query->query_vars['paged'] );
	$big = 999999999;

	$pagination = array(
		'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
		'format' => '',
		'total' => $query->max_num_pages,
		'current' => $current,
		'prev_text' => __( '&laquo; Previous', 'baca' ),
		'next_text' => __( 'Next &raquo;', 'baca' ),
		'end_size' => 1,
		'mid_size' => 2,
		'show_all' => true,
		'type' => 'list'
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
 * This functions will return post meta array, which is passed through the 'kc_post_meta' filter.
 *
 * @param $post_object Post object, either from global $post variable or using the get_post() function
 * @return array Post meta array
 *
 */

function kc_post_terms( $post_object = '' ) {
	if ( is_404() )
		return;

	if ( !$post_object ) {
		global $post;
		$post_object = $post;
	}

	if ( !is_object($post_object) )
		return false;

	$output = array();
	$taxonomies = get_taxonomies( array(
		'public'			=> true,
		'object_type'	=> array($post_object->post_type)
	), 'objects' );

	if ( !is_array($taxonomies) || empty($taxonomies) )
		return false;

	foreach ( $taxonomies as $taxonomy ) {
		$taxonomy_label = apply_filters( "kc_post_meta_taxo_label_{$taxonomy->name}", $taxonomy->label );
		if ( $post_tems = get_the_term_list($post_object->ID, $taxonomy->name, "<span class='label'>{$taxonomy_label}:</span> ", ', ') )
			$output[$taxonomy->name] = $post_tems;
	}

	return apply_filters( 'kc_post_meta', $output );
}

