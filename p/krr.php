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
function baca_check_req( $reqs, $message = '' ) {
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
function baca_doc_title( $title ) {
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
add_filter( 'wp_title', 'baca_doc_title' );