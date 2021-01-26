<?php
/**
 * nuri Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage nuri
 * @since nuri Theme 1.0
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function nuri_theme_support() {


	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Set post thumbnail size.
	set_post_thumbnail_size( 1200, 9999 );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );


	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'woocommerce',
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on nuri Theme, use a find and replace
	 * to change 'nuri' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'nuri' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );


	/*
	 * Adds `async` and `defer` support for scripts registered or enqueued
	 * by the theme.
	 */
	$loader = new blanky_Script_Loader();
	add_filter( 'script_loader_tag', array( $loader, 'filter_script_loader_tag' ), 10, 2 );

}

add_action( 'after_setup_theme', 'nuri_theme_support' );

/**
 * REQUIRED FILES
 * Include required files.
 */

require get_template_directory() . '/inc/template-tags.php';

// Custom script loader class.
require get_template_directory() . '/classes/class-blanky-script-loader.php';


/**
 * Register and Enqueue Styles.
 */
function nuri_register_styles() {

	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'nuri-style', get_template_directory_uri() . '/assets/css/style.css', array(), $theme_version );
	wp_style_add_data( 'nuri-style', 'rtl', 'replace' );

}

add_action( 'wp_enqueue_scripts', 'nuri_register_styles' );

/**
 * Register and Enqueue Scripts.
 */


function nuri_register_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );

	//Include WP jQuery
    wp_enqueue_script('jquery');

	if ( is_shop() ) {
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/js/isotope.min.js', array('jquery'), $theme_version, false );
	}

	wp_enqueue_script( 'custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), $theme_version, false );
	wp_script_add_data( 'custom', 'async', true ); }



add_action( 'wp_enqueue_scripts', 'nuri_register_scripts' );


/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function nuri_menus() {

	$locations = array(
		'primary'  => __( 'Primary Menu', 'nuri' ),
		'secondary'   => __( 'Secondary Menu', 'nuri' ),
	);

	register_nav_menus( $locations );
}

add_action( 'init', 'nuri_menus' );

/**
 * Enqueue supplemental block editor styles.
 */
function nuri_block_editor_styles() {

	// Enqueue the editor styles.
	wp_enqueue_style( 'nuri-block-editor-styles', get_theme_file_uri( '/assets/css/editor-style-block.css' ), array(), wp_get_theme()->get( 'Version' ), 'all' );
	wp_style_add_data( 'nuri-block-editor-styles', 'rtl', 'replace' );

	// Enqueue the editor script.
	wp_enqueue_script( 'nuri-block-editor-script', get_theme_file_uri( '/assets/js/editor-script-block.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), true );
}

add_action( 'enqueue_block_editor_assets', 'nuri_block_editor_styles', 1, 1 );


// Allow the Editor Role to change Theme Settings and use Customizer
$role_object = get_role( 'editor' );
$role_object->add_cap( 'edit_theme_options' );

// Advanced Custom Fields
if (class_exists('ACF')) {
	require get_template_directory() . '/inc/acf.php';
}

// Woocommerce related functions
if (class_exists('woocommerce')) {
	require get_template_directory() . '/inc/woo.php';
}

/**
* is_realy_woocommerce_page - Returns true if on a page which uses WooCommerce templates (cart and checkout are standard pages with shortcodes and which are also included)
*
* @access public
* @return bool
*/
function is_realy_woocommerce_page () {
    if( function_exists ( "is_woocommerce" ) && is_woocommerce()){
        return true;
    }
    $woocommerce_keys = array ( "woocommerce_shop_page_id" ,
        "woocommerce_terms_page_id" ,
        "woocommerce_cart_page_id" ,
        "woocommerce_checkout_page_id" ,
        "woocommerce_pay_page_id" ,
        "woocommerce_thanks_page_id" ,
        "woocommerce_myaccount_page_id" ,
        "woocommerce_edit_address_page_id" ,
        "woocommerce_view_order_page_id" ,
        "woocommerce_change_password_page_id" ,
        "woocommerce_logout_page_id" ,
        "woocommerce_lost_password_page_id" ) ;

    foreach ( $woocommerce_keys as $wc_page_id ) {
        if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
            return true ;
        }
    }
    return false;
}

function is_shop_manager() {
    $user = wp_get_current_user();
    if ( isset( $user['roles'][0] ) && $user['roles'][0] == 'shop_manager' ) {
        return true;    // when user is shop manager
    } else {
        return false;   // when user is not shop manager
    }
}

add_action( 'template_redirect', 'hide_woocommerce' );

function hide_woocommerce() {
	$is_admin = current_user_can('manage_options');
	if ( is_realy_woocommerce_page() && !$is_admin && !is_shop_manager() && !is_admin() ) {
		wp_redirect( home_url() );
		exit();
	}
}
