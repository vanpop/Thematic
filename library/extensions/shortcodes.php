<?php
/**
 * Shortcodes
 *
 * A set of shortcodes that get registered with the 
 * WordPress {@link http://codex.wordpress.org/Shortcode_API ShortCode API}.
 *
 * These can be used display information such as attributitive links 
 * for the framework, the active child theme, and more.
 *
 * @package ThematicCoreLibrary
 * @subpackage Shortcodes
 *
 */


/**
 * Display link to WP.org.
 */
function thematic_shortcode_wp_link() {
    return '<a class="wp-link" href="http://WordPress.org/" title="WordPress">WordPress</a>';
}
add_shortcode('wp-link', 'thematic_shortcode_wp_link');		  

		  
/**
 * Display link to Thematic.
 */
function thematic_shortcode_framework_link() {
    $themelink = '<a class="theme-link" href="http://thematictheme.com" title="Thematic Theme Framework" rel="home">Thematic Theme Framework</a>';
    return apply_filters('thematic_theme_link',$themelink);
}
add_shortcode('theme-link', 'thematic_shortcode_framework_link');	


/**
 * Display link to wp-admin of the site.
 */
function thematic_shortcode_login_link() {
    $link = wp_loginout( get_permalink(), FALSE ); 
    return apply_filters('loginout', $link);
}
add_shortcode('loginout-link', 'thematic_shortcode_login_link');		  	  


/**
 * Display the site title.
 */
function thematic_shortcode_blog_title() {
	return '<span class="blog-title">' . get_bloginfo('name', 'display') . '</span>';
}
add_shortcode('blog-title', 'thematic_shortcode_blog_title');


/**
 * Display the site title with a link to the site.
 */
function thematic_shortcode_blog_link() {
	return '<a href="' . site_url('/') . '" title="' . esc_attr( get_bloginfo('name', 'display') ) . '" >' . get_bloginfo('name', 'display') . "</a>";
}
add_shortcode('blog-link', 'thematic_shortcode_blog_link');


/**
 * Display the current year.
 */
function thematic_shortcode_year() {   
    return '<span class="the-year">' . date('Y') . '</span>';
}
add_shortcode('the-year', 'thematic_shortcode_year');


/**
 * Display the name of the parent theme.
 */
function thematic_shortcode_theme_name() {
    $frameworkData = wp_get_theme( 'thematic' );
    return $frameworkData->display( 'Name', false );
}
add_shortcode('theme-name', 'thematic_shortcode_theme_name');


/**
 * Display the name of the parent theme author.
 */
function thematic_shortcode_theme_author() {
    $frameworkData = wp_get_theme( 'thematic' );
    return $frameworkData->display( 'Author', false );
}
add_shortcode('theme-author', 'thematic_shortcode_theme_author');


/**
 * Display the URI of the parent theme.
 */
function thematic_shortcode_theme_uri() {
    $frameworkData = wp_get_theme( 'thematic' );
    return $frameworkData->display( 'ThemeURI', false );
}
add_shortcode('theme-uri', 'thematic_shortcode_theme_uri');


/**
 * Display the version no. of the parent theme.
 */
function thematic_shortcode_theme_version() {
    $frameworkData = wp_get_theme( 'thematic' );
    return trim( $frameworkData->display('Version', false));
}
add_shortcode('theme-version', 'thematic_shortcode_theme_version');



/**
 * Display the name of the child theme.
 */
function thematic_shortcode_child_name() {
    $frameworkData = wp_get_theme();
    return $frameworkData->display( 'Name', false );
}
add_shortcode('child-name', 'thematic_shortcode_child_name');


/**
 * Display the name of the child theme author.
 */
function thematic_shortcode_child_author() {
    $frameworkData = wp_get_theme();
    return $frameworkData->display( 'Author', false );
}
add_shortcode('child-author', 'thematic_shortcode_child_author');


/**
 * Display the URI of the child theme.
 */
function thematic_shortcode_child_uri() {
    $frameworkData = wp_get_theme();
    return $frameworkData->display( 'ThemeURI', false );
}
add_shortcode('child-uri', 'thematic_shortcode_child_uri');


/**
 * Display the version no. of the child theme.
 * 
 */
function thematic_shortcode_child_version() {
    $frameworkData = wp_get_theme();
    return trim( $frameworkData->display('Version', false));
}
add_shortcode('child-version', 'thematic_shortcode_child_version');
