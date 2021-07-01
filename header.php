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

		<!-- Google Analytics -->
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-191449261-1', 'auto');
		ga('send', 'pageview');
		</script>
		<!-- End Google Analytics -->
		<style>
			.includes_GST span bdi {
				display:none;
			}
		</style>

	</head>

	<body <?php body_class(); ?>>

		<?php if($logo){
			echo '<a href="'. home_url() .'" class="custom-logo logo">
				<img src="'. esc_url($logo) .'" class="style-svg" />
			</a>';
		} else {
			echo '<a href="'. home_url() .'" class="custom-logo logo">'. get_bloginfo('name') .'</a>';
		} ?>

		<div class="nav-toggle" id="nav-toggle" aria-expanded="false">
			 <span></span>
		</div>

		<header id="site-header" role="banner">

			<div class="header-inner section-inner">

			<?php if ( has_nav_menu( 'primary' ) ) { ?>
				<nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e( 'Horizontal', 'nuri' ); ?>" role="navigation">
					<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary',  ) ); ?>
					<svg version="1.1" id="search-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
						 viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve" width="29">
						<style type="text/css">
							.st0{fill:none;stroke:#000000;}
						</style>
						<g id="Gruppe_54" transform="translate(-1752.55 -90)">
							<g id="Ellipse_8" transform="translate(1760 90)">
								<circle class="st0" cx="16.3483" cy="15.9518" r="12.5"/>
							</g>
							<line id="Linie_4" class="st0" x1="1765.4442" y1="117.3558" x2="1756.2518" y2="126.5482"/>
						</g>
					</svg>
					<?php if (class_exists('woocommerce')) { header_cart(); } ?>
				</nav>
			<?php } ?>

			</div><!-- .header-inner -->

		</header><!-- #site-header -->

<div class="wrapper">
