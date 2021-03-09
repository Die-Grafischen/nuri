<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
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

?>
</div><!-- .wrapper -->
		<footer id="site-footer" role="contentinfo">

			<?php
				$footer = get_field('footer', 'options');
				$logo = $footer['logo'];
				$text = $footer['textblock'];
				$social = $footer['social_media'];
				$copyright = $footer['copyright'];
			?>

			<div class="section-inner">

				<nav class="primary-menu-wrapper" aria-label="<?php esc_attr_e( 'Horizontal', 'nuri' ); ?>" role="navigation">
					<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'primary',  ) ); ?>
				</nav>

				<?php if($logo) {
					echo '<div class="logo-wrapper">
						<a href="'. home_url() .'" class="logo-footer">
							<img src="'. esc_url($logo) .'" class="style-svg" alt="'. get_bloginfo('name') .'" />
						</a>
					</div>';
				} ?>

				<?php if( $text || $social ) {
					echo '<div class="text-social">';

					if($text) {
						echo '<div class="footer-text">
							'. wp_kses_post($text) .'
						</div>';
					}

					if($social) {
						echo '<div class="footer-social">';
						foreach ($social as $media) {
							echo '<a href="'. esc_url($media['url']) .'" target="_blank">
								<img src="'. esc_url($media['icon']) .'" alt="" class="style-svg"/>
							</a>';
						}
						echo '</div>';
					}

					echo '</div>';
				} ?>

				<div class="footer-credits">

					<p class="footer-copyright">&copy;
						<?php
						echo date_i18n(
							/* translators: Copyright date format, see https://www.php.net/date */
							_x( 'Y', 'copyright date format', 'nuri' )
						);
						?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php if($copyright) {
							echo ''. esc_html($copyright) .'';
						} else {
							bloginfo( 'name' );
						}?></a>
					</p><!-- .footer-copyright -->

					<nav class="secondary-navi" aria-label="<?php esc_attr_e( 'Horizontal', 'nuri' ); ?>" role="navigation">
						<?php wp_nav_menu( array( 'container' => false, 'theme_location' => 'secondary',  ) ); ?>
					</nav>

				</div><!-- .footer-credits -->

			</div><!-- .section-inner -->

		</footer><!-- #site-footer -->

		<div id="search-overlay"><?php if ( function_exists( 'aws_get_search_form' ) ) { aws_get_search_form(); } ?></div>

		<?php wp_footer(); ?>

	</body>
</html>
