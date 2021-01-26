<?php
/**
 * Header file for the WP Blank theme.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Nuri
 * @since Nuri 1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>
		<?php
			$header = get_field('header', 'options');
			$icon = $header['icon'];
			$logo = $header['logo'];
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<link rel="shortcut icon" href="<?php echo esc_url($icon); ?>" />
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php if($logo){
			echo '<a href="'. home_url() .'" class="custom-logo logo">
				<img src="'. esc_url($logo) .'" class="style-svg" />
			</a>';
		} else {
			echo '<a href="'. home_url() .'" class="custom-logo logo">'. get_bloginfo('name') .'</a>';
		} ?>

		<div id="nav-toggle" aria-expanded="false">
			 <span></span>
		</div>

		<header id="site-header" role="banner">

			<div class="header-inner section-inner">


			<?php if ( has_nav_menu( 'primary' ) ) { ?>
				<nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e( 'Horizontal', 'nuri' ); ?>" role="navigation">
					<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary',  ) ); ?>

					<?php if (class_exists('woocommerce')) { header_cart(); } ?>
				</nav>
			<?php } ?>

			</div><!-- .header-inner -->

		</header><!-- #site-header -->

<div class="wrapper">
	<?php /*
	$posts_data = array();
	// $paged = $request->get_param('page');
	// $paged = (isset($paged) || !(empty($paged))) ? $paged : 1;
	$args = array(
	  'status'          => 'publish',
	  'page'           => 1
	);

	$products = wc_get_products( $args );
	foreach ($products as $product) {

		$product_id = $product->get_id();
		$product_title = $product->get_title();
		$product_price = $product->get_price();
		$product_category_ids = $product->get_category_ids();
		$terms = get_the_terms( $product_id, 'product_cat' );




		// var_dump($product_category_ids);

		//print $product->get_title() . ' ' . $product->get_price();
		// $posts_data[] = (object)array(
		// 	'product_class' => $product->get_title()
		// );
	}



	wp_reset_postdata(); */ ?>
