<?php
/**
 * Header Extensions
 *
 * @package ThematicCoreLibrary
 * @subpackage HeaderExtensions
 */
  


if ( function_exists( 'childtheme_override_doctype' ) )  {
    /**
     * @ignore
     */
    function thematic_doctype() {
    	childtheme_override_doctype();
    }
} else {
	/**
	 * Display the DOCTYPE
	 * 
	 * Override: childtheme_doctype
	 */
	function thematic_doctype() {
?>
<!DOCTYPE html>
<?php
	}

}


if ( function_exists( 'childtheme_override_html' ) )  {
	/**
     * @ignore
     */
    function thematic_html() {
    	childtheme_override_html();
    }
} else {
	/**
	* Display the html tag and attributes
	* Override: childtheme_html
	* Filter: thematic_html_class for including a class attribute string
	*/
	function thematic_html( $class_att = FALSE ) {
		$html_class = apply_filters( 'thematic_html_class' , $class_att );
?>
<!--[if lt IE 7]><html class="<?php if ( $html_class ) echo( $html_class . ' ' ) ?>lt-ie9 lt-ie8 lt-ie7" <?php language_attributes() ?>><![endif]-->
<!--[if IE 7]><html class="<?php if ( $html_class ) echo( $html_class . ' ' ) ?>ie7 lt-ie9 lt-ie8" <?php language_attributes() ?>><![endif]-->
<!--[if IE 8]><html class="<?php if ( $html_class ) echo( $html_class . ' ' ) ?>ie8 lt-ie9" <?php language_attributes() ?>><![endif]-->
<!--[if gt IE 8]><!--><html class="<?php if ( $html_class ) echo $html_class ?>" <?php language_attributes() ?>><!--<![endif]-->

<?php
	}
}


if ( function_exists( 'childtheme_override_head' ) )  {
	/**
     * @ignore
     */
    function thematic_head() {
    	childtheme_override_head();
    }
} else {
	/**
	 * Display the HEAD 
 	 * 
 	 */
	function thematic_head() {
?>
<head>

<?php
	}
}


if ( function_exists( 'childtheme_override_meta_charset' ) ) {
	/**
	 * @ignore
	 */
	 function thematic_meta_charset() {
		childtheme_override_meta_charset();
	 }
} else {
	/**
	 * Display the head meta charset
	 * 
	 * Override: childtheme_override_meta_charset
	 */
	function thematic_meta_charset() {
?>
<meta charset="<?php echo ( get_bloginfo( 'charset' ) ) ?>" />
<?php
	}
}


/**
 * Display the meta viewport
 *
 * Filter: thematic_meta_viewport_content
 *
 */
function thematic_meta_viewport() {
	$viewport_content = apply_filters( 'thematic_meta_viewport_content', 'width=device-width' ); 
?>
<meta name="viewport" content="<?php echo $viewport_content ?>"/>
<?php
}


if ( function_exists( 'childtheme_override_doctitle' ) )  {
	/**
	 * @ignore
	 */
	 function thematic_doctitle() {
    	childtheme_override_doctitle();
    }
} else {
	/**
	 * Display the content of the title tag
	 * 
	 * Override: childtheme_override_doctitle
	 * Filter: thematic_doctitle_separator
	 *
	 */
	function thematic_doctitle() {
        $separator = apply_filters( 'thematic_doctitle_separator', '|' );
        $doctitle = '<title>' . wp_title( $separator, false, 'right' ) . '</title>' . "\n";
        echo $doctitle;
	} // end thematic_doctitle
}

	
/**
 * Filters wp_title returning the doctitle contents
 * Located in header.php Credits: Tarski Theme
 * 
 * Override: childtheme_override_doctitle
 * Filter: thematic_doctitle_separator
 * Filter: thematic_doctitle
 *
 * @since 1.0.2
 */
function thematic_wptitle( $wp_doctitle, $separator, $sep_location ) { 
	// return original string if on feed or if a seo plugin is being used
    if ( is_feed() || !thematic_seo() )
    	return $wp_doctitle;
   	// otherwise...	
   	$site_name = get_bloginfo( 'name' , 'display' );
        	
    if ( is_single() || ( is_home() && !is_front_page() ) || ( is_page() && !is_front_page() ) ) {
      $content = single_post_title( '', FALSE );
    }
    elseif ( is_front_page() ) { 
      $content = get_bloginfo( 'description', 'display' );
    }
    elseif ( is_page() ) { 
      $content = single_post_title( '', FALSE ); 
    }
    elseif ( is_search() ) { 
      $content = __( 'Search Results for:', 'thematic' ); 
      $content .= ' ' . get_search_query();
    }
    elseif ( is_category() ) {
      $content = __( 'Category Archives:', 'thematic' );
      $content .= ' ' . single_cat_title( '', FALSE );
    }
    elseif ( is_tag() ) { 
      $content = __( 'Tag Archives:', 'thematic' );
      $content .= ' ' . thematic_tag_query();
    }
    elseif ( is_404() ) { 
      $content = __( 'Not Found', 'thematic' ); 
    }
    else { 
      $content = get_bloginfo( 'description', 'display' );
    }
    
    if ( get_query_var( 'paged' ) ) {
      $content .= ' ' .$separator. ' ';
      $content .= 'Page';
      $content .= ' ';
      $content .= get_query_var( 'paged' );
    }
    
    if( $content ) {
      if ( is_front_page() ) {
          $elements = array(
            'site_name' => $site_name,
            'separator' => $separator,
            'content' 	=> $content
          );
      }
      else {
          $elements = array(
            'content' => $content
          );
      }  
    } else {
      $elements = array(
        'site_name' => $site_name
      );
    }
    
    // Filters should return an array
    $elements = apply_filters( 'thematic_doctitle', $elements );
       
    // But if they don't, it won't try to implode
    if( is_array( $elements ) ) {
        $thematic_doctitle = implode( ' ', $elements );
    } else {
   	    $thematic_doctitle = $elements;
    }
   	
    return $thematic_doctitle;
}

add_filter( 'wp_title', 'thematic_wptitle', 10, 3 );


/**
 * Switch Thematic's SEO functions on or off
 * 
 * Provides compatibility with SEO plugins: All in One SEO Pack, HeadSpace, 
 * Platinum SEO Pack, wpSEO and Yoast SEO. Default: ON
 * 
 * Filter: thematic_seo
 */
function thematic_seo() {
	if ( class_exists( 'All_in_One_SEO_Pack' ) || class_exists( 'HeadSpace_Plugin' ) || class_exists( 'Platinum_SEO_Pack' ) || class_exists( 'wpSEO' ) || defined( 'WPSEO_VERSION' ) ) {
		$content = FALSE;
	} else {
		$content = true;
	}
		return apply_filters( 'thematic_seo', $content );
}


/**
 * Switch use of thematic_the_excerpt() in the meta-tag description
 * 
 * Default: ON
 * 
 * Filter: thematic_use_excerpt
 */
function thematic_use_excerpt() {
    $display = TRUE;
    $display = apply_filters( 'thematic_use_excerpt', $display );
    return $display;
}


/**
 * Switch use of thematic_use_autoexcerpt() in the meta-tag description
 * 
 * Default: OFF
 * 
 * Filter: thematic_use_autoexcerpt
 */
function thematic_use_autoexcerpt() {
    $display = FALSE;
    $display = apply_filters( 'thematic_use_autoexcerpt', $display );
    return $display;
}

	
/**
 * Display the meta-tag description
 * 
 * This can be switched off by filtering either thematic_seo or thematic_show_description and returning FALSE
 * 
 * Filter: thematic_show_description boolean filter to to display the meta description defaults to ON
 * Filter: thematic_use_autoexcerpt  boolean filter to switch ON auto-excerpted descriptions defaults to OFF
 * Filter: thematic_use_autoexcerpt  boolean filter to switch OFF auto-excerpted descriptions defaults to ON
 * Filter: thematic_create_description
 */
function thematic_meta_description() {
	if ( thematic_seo() ) {
		$display = apply_filters( 'thematic_show_description', $display = TRUE );
		if ( $display ) {
			$content = '';
    		if ( is_single() || is_page() ) {
      			if ( have_posts() ) {
          			while ( have_posts() ) {
            			the_post();
						if ( thematic_the_excerpt() == "" ) {
							if ( apply_filters( 'thematic_use_autoexcerpt', $display = FALSE ) ) {
					    		$content = '<meta name="description" content="';
                    			$content .= thematic_excerpt_rss();
                    			$content .= '" />';
                    			$content .= "\n";
							}
						} else {
							if ( apply_filters( 'thematic_use_excerpt', $display = TRUE ) ) {
                    			$content = '<meta name="description" content="';
                    			$content .= thematic_the_excerpt();
                    			$content .= '" />';
                    			$content .= "\n";
                        	}
                		}
            		}
        		}
        	} elseif ( is_home() || is_front_page() ) {
    			$content = '<meta name="description" content="';
    			$content .= get_bloginfo( 'description', 'display' );
    			$content .= '" />';
    			$content .= "\n";
        	}
			echo apply_filters ( 'thematic_create_description', $content );
		}
	} // end thematic_meta_description
}


/**
 * Create the robots meta-tag
 * 
 * This can be switched off by filtering either thematic_seo or thematic_show_robots and returning FALSE
 * 
 * Filter: thematic_show_robots
 * Filter: thematic_create_robots
 */
function thematic_meta_robots() {
	global $paged;
	if ( thematic_seo() && get_option( 'blog_public' ) ) {
		$display = apply_filters( 'thematic_show_robots', $display = TRUE );
		if ( $display ) {
    		if ( ( is_home() && ( $paged < 2 ) ) || is_front_page() || is_single() || is_page() || is_attachment() ) {
				$content = '';
    		} elseif ( is_search() ) {
        		$content = '<meta name="robots" content="noindex,nofollow" />';
    		} else {	
        		$content = '<meta name="robots" content="noindex,follow" />';
    		}
    	$content .= "\n";
    	echo apply_filters( 'thematic_create_robots', $content );
    	}
	}
}// end thematic_meta_robots


/**
 * Display links to RSS feed
 * 
 * This can be switched on or off using thematic_show_rss. Default: ON
 * 
 * Filter: thematic_show_rss
 * Filter: thematic_rss
 */
function thematic_show_rss() {
    $display = apply_filters( 'thematic_show_rss', $display = TRUE );
    if ( $display ) {
        $content = '<link rel="alternate" type="application/rss+xml" href="';
        $content .= get_feed_link( get_default_feed() );
        $content .= '" title="';
        $content .= esc_attr( get_bloginfo( 'name', 'display' ) );
        $content .= ' ' . __( 'Posts RSS feed', 'thematic' );
        $content .= '" />';
        $content .= "\n";
        echo apply_filters( 'thematic_rss', $content );
    }
}


/**
 * Display links to RSS feed for comments
 * 
 * This can be switched on or off using thematic_show_commentsrss. Default: ON
 * 
 * Filter: thematic_show_commentsrss
 * Filter: thematic_commentsrss
 */
function thematic_show_commentsrss() {
    $display = apply_filters( 'thematic_show_commentsrss', $display = TRUE );
    if ( $display ) {
        $content = '<link rel="alternate" type="application/rss+xml" href="';
        $content .= get_feed_link( 'comments_' . get_default_feed() );
        $content .= '" title="';
        $content .= esc_attr( get_bloginfo('name') );
        $content .= ' ' . __( 'Comments RSS feed', 'thematic' );
        $content .= '" />';
        $content .= "\n";
        echo apply_filters( 'thematic_commentsrss', $content );
    }
}


/**
 * Display pingback link
 * 
 * This can be switched on or off using thematic_show_pingback. Default: ON
 * 
 * Filter: thematic_show_pingback
 * Filter: thematic_pingback_url
 */
function thematic_show_pingback() {
    $display = apply_filters( 'thematic_show_pingback', $display = TRUE );
    if ( $display ) {
        $content = '<link rel="pingback" href="';
        $content .= get_bloginfo( 'pingback_url' );
        $content .= '" />';
        $content .= "\n";
        echo apply_filters( 'thematic_pingback_url', $content );
    }
}


/**
 * Add html5 shiv for older browser compatibility
 * 
 * Filter: thematic_modernizr_handles
 * Filter: thematic_use_html5shiv
 * Filter: thematic_html5shiv_output
 * 
 * @since 2.0
 */
function thematic_add_html5shiv() {
	
	$use_shiv = true;
	
	// List of handles to look for. These scripts make the html5shiv unnecessary
	$possible_handles = array(
		'modernizr',
		'modernizr-js'
	);
	
	/**
	 * Filter the possible script handles that makes the html5 shiv unnecessary.
	 * 
	 * The handles are strings used as id in the call to <code>wp_enqueue_script()</code>.
	 * If a script with any of these handles is enqueued by a child theme or plugin, Thematic
	 * will not add the html5 shiv.
	 * 
	 * @since 2.0
	 * 
	 * @param  array  $possible_handles  Array of handle names
	 */
	$possible_handles = apply_filters( 'thematic_modernizr_handles', $possible_handles );
	
	// Check if any other scripts has been enqueued
	foreach( $possible_handles as $handle) {
		if( wp_script_is( $handle, 'queue' ) )
			$use_shiv = false;
	}
	
	/**
	 * Decide whether to use the html5shiv or not
	 * 
	 * Provides a shortcut to switch off the shiv. Defaults to true,
	 * unless modernizr is detected.
	 * 
	 * @since 2.0
	 * 
	 * @param  boolean  $use_shiv
	 */
	$use_shiv = apply_filters( 'thematic_use_html5shiv', $use_shiv );
	
	// Output script link
	if( $use_shiv ) {
		$content  = '<!--[if lt IE 9]>' . "\n";
		$content .= '<script src="' . get_template_directory_uri() . '/library/js/html5.js"></script>' . "\n";
		$content .= '<![endif]-->';
	
		/**
		 * Filter the output string of the html5shiv link
		 * 
		 * @since 2.0
		 * 
		 * @param  string  $content  The complete string that gets output to wp_head
		 */
		echo apply_filters( 'thematic_html5shiv_output', $content );
	}
	
}

add_action( 'wp_head', 'thematic_add_html5shiv', 20 );


/**
 * Add the default stylesheet to the head of the document.
 * 
 * Register and enqueue Thematic main stylesheet and child style.css
 *
 * Child themes that want to filter the wp_enqueue_style params
 * should call wp_register_style( '{$themeslug}' , $custom , $custom, $custom )
 * hooking into wp_enqueue_scripts. The theme slug is the name of the folder 
 * of your child theme.
 * 
 * If you want to use the Genericons icon font in a child theme, filter 
 * thematic_childtheme_style_dependencies and pass an array including the 
 * 'genericons' handle.
 */
function thematic_create_stylesheet() {
	$theme = wp_get_theme();
	$themeslug = get_stylesheet();
	$template = wp_get_theme( 'thematic' );	
	
	/**
	 * Filter for specifying child theme stylesheet dependencies
	 * 
	 * @param array List of registered style handles
	 */
	$childtheme_style_dependencies = apply_filters( 'thematic_childtheme_style_dependencies', array( 'thematic-style1' ) );
	
	wp_register_style( 'genericons', get_template_directory_uri() . '/library/css/genericons.css', array(), '3.0.2' );
	wp_register_style( 'thematic-style1', get_template_directory_uri() . '/library/css/style1.css', array(), $template->Version );
	
	wp_enqueue_style( "{$themeslug}", get_stylesheet_uri(), $childtheme_style_dependencies, $theme->Version );
}
add_action( 'wp_enqueue_scripts', 'thematic_create_stylesheet' );


if ( function_exists( 'childtheme_override_head_scripts' ) )  {
    /**
     * @ignore
     */
    function thematic_head_scripts() {
    	childtheme_override_head_scripts();
    }
} else {
    /**
     * Adds comment reply and navigation menu scripts to the head of the document.
     *
     * Child themes should use wp_dequeue_scripts to remove individual scripts.
     * Larger changes can be made using the override.
     *
     * Override: childtheme_override_head_scripts <br>
     *
     * For Reference: {@link http://users.tpg.com.au/j_birch/plugins/superfish/#getting-started Superfish Jquery Plugin}
     *
     * @since 1.0
     */
    function thematic_head_scripts() {
		$template = wp_get_theme( 'thematic' );

		// load comment reply script on posts and pages when option is set and check for deprecated filter
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
			has_filter( 'thematic_show_commentreply' ) ? thematic_show_commentreply() : wp_enqueue_script( 'comment-reply' );

		// load jquery and superfish associated plugins when theme support is active
		if ( current_theme_supports( 'thematic_superfish' ) ) {
			wp_enqueue_script( 'superfish', get_template_directory_uri() . '/library/js/superfish.min.js', array( 'jquery', 'hoverIntent' ), '1.7.4', true );
		}

		// load theme javascript in footer
		wp_enqueue_script( 'thematic-js', get_template_directory_uri() . '/library/js/thematic.js', array( 'jquery' ), $template->Version, true );
					

		$thematic_javascript_options = array( 
			'mobileMenuBreakpoint' => 600,
			'superfish' => array(
				// These are the options for the superfish dropdown menus
				// see http://users.tpg.com.au/j_birch/plugins/superfish/options/ for more details
				'animation'    => array( 'opacity' => 'show', 'height' => 'show' ), // animation on opening the submenu
				'hoverClass'   => 'sfHover',           // the class applied to hovered list items
				'pathClass'    => 'overideThisToUse',  // the class you have applied to list items that lead to the current page
				'pathLevels'   => 1,                   // the number of levels of submenus that remain open or are restored using pathClass
				'delay'        => 400,                 // the delay in milliseconds that the mouse can remain outside a submenu without it closing
				'speed'        => 'slow',              // speed of the opening animation. Equivalent to second parameter of jQuery’s .animate() method
				'cssArrows'    => false,               // set to false if you want to remove the CSS-based arrow triangles
				'disableHI'    => false                // set to true to disable hoverIntent detection
			) 
		);
		
		/**
		 * Filter the variables sent to wp_localize_script
		 * 
		 * @since 2.0
		 * 
		 * @param array $thematic_javascript_options
		 */
		$thematic_javascript_options = apply_filters( 'thematic_javascript_options', $thematic_javascript_options );
		
		wp_localize_script( 'thematic-js', 'thematicOptions', $thematic_javascript_options );
 	}
 }

add_action( 'wp_enqueue_scripts','thematic_head_scripts' );


/**
 * Return the default arguments for wp_page_menu()
 * 
 * This is used as fallback when the user has not created a custom nav menu in wordpress admin
 * 
 * Filter: thematic_page_menu_args
 *
 * @return array
 */
function thematic_page_menu_args() {
	$args = array (
		'sort_column' => 'menu_order',
		'menu_class'  => 'menu',
		'include'     => '',
		'exclude'     => '',
		'echo'        => FALSE,
		'show_home'   => FALSE,
		'link_before' => '',
		'link_after'  => ''
	);
	return apply_filters( 'thematic_page_menu_args', $args );
}


/**
 * Return the default arguments for wp_nav_menu
 * 
 * Filter: thematic_primary_menu_id <br>
 * Filter: thematic_nav_menu_args
 *
 * @return array
 */
function thematic_nav_menu_args() {
	$args = array (
		'theme_location'	=> apply_filters( 'thematic_primary_menu_id', 'primary-menu' ),
		'menu'				=> '',
		'container'			=> 'nav',
		'container_class'	=> 'menu',
		'menu_class'		=> 'sf-menu',
		'fallback_cb'		=> 'wp_page_menu',
		'before'			=> '',
		'after'				=> '',
		'link_before'		=> '',
		'link_after'		=> '',
		'depth'				=> 0,
		'walker'			=> '',
		'echo'				=> false
	);
	
	return apply_filters( 'thematic_nav_menu_args', $args );
}


/**
 * Switch adding superfish css class to wp_page_menu
 * 
 * This adds a css class of "sf-menu" to the first <ul> of wp_page_menu. Default: ON
 * Switchable using included filter.
 * 
 * Filter: thematic_use_superfish
 *
 * @param string
 * @return string
 */
function thematic_add_menuclass($ulclass) {
	if ( apply_filters( 'thematic_use_superfish', TRUE ) ) {
		return preg_replace( '/<ul>/', '<ul class="sf-menu">', $ulclass, 1 );
	} else {
		return $ulclass;
	}
}


if ( function_exists( 'childtheme_override_body' ) )  {
	/**
	 * @ignore
	 */
	 function thematic_body() {
		childtheme_override_body();
	}
} else {
	/**
	 * Creates the body tag
	 */
	 function thematic_body() {
    	if ( apply_filters( 'thematic_show_bodyclass',TRUE ) ) { 
        	// Creating the body class
    		echo '<body ';
    		body_class();
    		echo '>' . "\n\n";
    	} else {
    		echo '<body>' . "\n\n";
    	}
	}
}


/**
 * Register action hook: thematic_before
 * 
 * Located in header.php, just after the body tag, before anything else.
 */
function thematic_before() {
    do_action( 'thematic_before' );
}


/**
 * Register action hook: thematic_abovefooter
 * 
 * Located in header.php, inside the header div
 */
function thematic_aboveheader() {
    do_action( 'thematic_aboveheader' );
}


/**
 * Register action hook: thematic_abovefooter
 * 
 * Located in header.php, inside the header div
 */
function thematic_header() {
    do_action( 'thematic_header' );
}


if ( function_exists( 'childtheme_override_brandingopen' ) )  {
	/**
	 * @ignore
	 */
	function thematic_brandingopen() {
		childtheme_override_brandingopen();
		}
	} else {
	/**
	 * Display the opening of the #branding div
	 * 
	 * Override: childtheme_override_brandingopen
	 */
    function thematic_brandingopen() {
    	echo "\t<div id=\"branding\" class=\"branding\">\n";
    }
}

add_action( 'thematic_header','thematic_brandingopen',1 );


if ( function_exists( 'childtheme_override_blogtitle' ) )  {
	/**
	 * @ignore
	 */
    function thematic_blogtitle() {
    	childtheme_override_blogtitle();
    }
} else {
    /**
     * Display the blog title within the #branding div
     * 
     * Override: childtheme_override_blogtitle
     */    
    function thematic_blogtitle() { 
    ?>
    
    	<div id="blog-title" class="site-title"><span><a href="<?php echo home_url() ?>/" title="<?php bloginfo('name') ?>" rel="home"><?php bloginfo('name') ?></a></span></div>
    
    <?php 
    }
}

add_action( 'thematic_header','thematic_blogtitle', 3 );


if ( function_exists( 'childtheme_override_blogdescription' ) )  {
	/**
	 * @ignore
	 */
    function thematic_blogdescription() {
    	childtheme_override_blogdescription();
    }
} else {
    /**
     * Display the blog description within the #branding div
     * 
     * Override: childtheme_override_blogdescription
     */
    function thematic_blogdescription() {
    	if ( is_home() || is_front_page() ) { 
			$tag = 'h1';
		} else {	
			$tag = 'div';
		}
		echo "\t<$tag id=\"blog-description\" class=\"tagline\">". get_bloginfo( 'description', 'display' ) . "</$tag>\n\n";
    }
}

add_action('thematic_header','thematic_blogdescription',5);


if ( function_exists('childtheme_override_brandingclose') )  {
	/**
	 * @ignore
	 */
	 function thematic_brandingclose() {
    	childtheme_override_brandingclose();
    }
} else {
    /**
     * Display the closing of the #branding div
     * 
     * Override: childtheme_override_brandingclose
     */    
    function thematic_brandingclose() {
    	echo "\t\t" . '</div><!--  #branding -->' . "\n";
    }
}

add_action( 'thematic_header', 'thematic_brandingclose', 7 );


if ( function_exists( 'childtheme_override_access' ) )  {
    /**
	 * @ignore
	 */
	 function thematic_access() {
    	childtheme_override_access();
    }
} else {
    /**
     * Display the #access div
     * 
     * Override: childtheme_override_access
     */    
    function thematic_access() { 
    ?>
    
    <div id="access" role="navigation">
    	<h3 class="menu-toggle"><?php echo apply_filters( 'thematic_mobile_navigation_buttontext', _x( 'Menu', 'Mobile navigation button', 'thematic' ) ); ?></h3>
    	<div class=""><a class="skip-link screen-reader-text" href="#content" title="<?php esc_attr_e( 'Skip navigation to the content', 'thematic' ); ?>"><?php _e( 'Skip to content', 'thematic' ); ?></a></div><!-- .skip-link -->
    	
    	<?php 
    	if ( ( function_exists( 'has_nav_menu' ) ) && ( has_nav_menu( apply_filters( 'thematic_primary_menu_id', 'primary-menu' ) ) ) ) {
    	    echo  wp_nav_menu(thematic_nav_menu_args());
    	} else {
    	    echo  thematic_add_menuclass( wp_page_menu( thematic_page_menu_args () ) );	
    	}
    	?>
    	
    </div><!-- #access -->
    <?php 
    }
}

add_action( 'thematic_header', 'thematic_access', 9 );


/**
 * Register action hook: thematic_belowheader
 * 
 * Located in header.php, just after the header div
 */
function thematic_belowheader() {
    do_action( 'thematic_belowheader' );
} // end thematic_belowheader
		

?>
