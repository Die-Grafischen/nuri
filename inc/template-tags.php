<?php
/**
 * Custom template tags for this theme.
 *
 * @package WordPress
 * @subpackage WPBlank
 * @since WPBlank 1.0
 */

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }


/**
 * Logo & Description
 */
/**
 * Displays the site logo, either text or image.
 *
 * @param array   $args Arguments for displaying the site logo either as an image or text.
 * @param boolean $echo Echo or return the HTML.
 *
 * @return string $html Compiled HTML based on our arguments.
 */
function wpblank_site_logo( $args = array(), $echo = true ) {
	$logo       = get_custom_logo();
	$site_title = get_bloginfo( 'name' );
	$contents   = '';
	$classname  = '';
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="180" height="60.104" viewBox="0 0 180 60.104">
	  <g id="Group_1" data-name="Group 1" transform="translate(-382.362 -283.181)">
	    <path id="Path_1" data-name="Path 1" d="M453.741,319.749a2.949,2.949,0,0,1-1.451.4q-1.452,0-1.451-2.5V290.729H432.62v1.694a5.348,5.348,0,0,1,3.143,1.008q.968.845.968,3.183v16.121q0,6.693-1.814,10.439t-5.037,3.748a3.843,3.843,0,0,1-3.468-1.974,10.772,10.772,0,0,1-1.209-5.6V290.729h-17.33v1.694a4.265,4.265,0,0,1,2.377.886,3.152,3.152,0,0,1,.846,2.5V318.7q0,6.612,3.385,10.279T424,332.647a14.04,14.04,0,0,0,8.5-2.62,13.38,13.38,0,0,0,4.957-7.134h.16l2.1,10.156h1.291l15.233-12.736-.806-1.451Z" transform="translate(34.592 10.234)" fill="#fff"/>
	    <path id="Path_2" data-name="Path 2" d="M458.778,290.551a9.094,9.094,0,0,0-6.571,2.82,15.343,15.343,0,0,0-3.991,7.579h-.16l-.565-10.8h-1.449l-14.35,8.382v1.531q3.223,0,3.225,2.742V327.47a3.149,3.149,0,0,1-.848,2.5,4.265,4.265,0,0,1-2.377.886v1.692h22.975v-1.692a11.724,11.724,0,0,1-4.434-1.048,2.757,2.757,0,0,1-1.209-2.58V312.395a47.98,47.98,0,0,1,.806-10.439q.8-3.262,2.983-3.263a1.923,1.923,0,0,1,1.289.483,12.157,12.157,0,0,1,1.451,1.611,12.028,12.028,0,0,0,2.217,2.379,4.4,4.4,0,0,0,2.78.846,5.854,5.854,0,0,0,4.314-1.734A6.056,6.056,0,0,0,466.6,297.8a6.815,6.815,0,0,0-2.177-5.24A7.981,7.981,0,0,0,458.778,290.551Z" transform="translate(66.886 9.447)" fill="#fff"/>
	    <path id="Path_3" data-name="Path 3" d="M453.262,295.151a9.965,9.965,0,0,0,11.69,0,6.673,6.673,0,0,0,0-10.076,9.965,9.965,0,0,0-11.69,0,6.674,6.674,0,0,0,0,10.076Z" transform="translate(93.019)" fill="#fff"/>
	    <path id="Path_4" data-name="Path 4" d="M468.219,329.738a3.148,3.148,0,0,1-.846-2.5V290.319h-1.451L450.041,298.7v1.531q3.223,0,3.225,2.742v24.263a3.157,3.157,0,0,1-.846,2.5,4.281,4.281,0,0,1-2.38.886v1.692H470.6v-1.692A4.266,4.266,0,0,1,468.219,329.738Z" transform="translate(91.766 9.678)" fill="#fff"/>
	    <path id="Path_5" data-name="Path 5" d="M420.61,307.951l-5.567-6.907-13.577-16.849H382.845v1.854a4.087,4.087,0,0,1,3.185,1.291A4.415,4.415,0,0,1,387.2,290.4v36.757a22.058,22.058,0,0,1-1.291,8.425q-1.29,3.022-3.546,3.183v1.854h18.7v-1.854q-5.481-.318-8.382-3.023t-2.9-8.262V292.739l38.451,47.881h2.58V320.607l-2.58-3.2Z" transform="translate(0 1.374)" fill="#fff"/>
	    <path id="Path_6" data-name="Path 6" d="M397.041,286.048q5.481.325,8.382,3.023t2.9,8.262v12.72l2.58,3.2v-15.6a22.059,22.059,0,0,1,1.289-8.422q1.29-3.025,3.548-3.185v-1.854h-18.7Z" transform="translate(19.903 1.374)" fill="#fff"/>
	  </g>
	</svg>';

	$defaults = array(
		'logo'        => '%1$s<span class="screen-reader-text">%2$s</span>',
		'logo_class'  => 'site-logo',
		'title'       => '<a href="%1$s">%2$s</a>',
		'title_class' => 'site-title',
		'home_wrap'   => '<h1 class="%1$s">%2$s</h1>',
		'single_wrap' => '<div class="%1$s faux-heading">%2$s</div>',
		'condition'   => ( is_front_page() || is_home() ) && ! is_page(),
	);

	$args = wp_parse_args( $args, $defaults );

	/**
	 * Filters the arguments for `wpblank_site_logo()`.
	 *
	 * @param array  $args     Parsed arguments.
	 * @param array  $defaults Function's default arguments.
	 */
	$args = apply_filters( 'wpblank_site_logo_args', $args, $defaults );

	if ( has_custom_logo() ) {
		$contents  = sprintf( $args['logo'], $logo, esc_html( $site_title ) );
		$classname = $args['logo_class'];
	} else {
		$contents  = sprintf( $args['title'], esc_url( get_home_url( null, '/' ) ), esc_html( $site_title ) );
		$classname = $args['title_class'];
	}

	$wrap = $args['condition'] ? 'home_wrap' : 'single_wrap';

	$html = sprintf( $args[ $wrap ], $classname, $contents );

	/**
	 * Filters the arguments for `wpblank_site_logo()`.
	 *
	 * @param string $html      Compiled html based on our arguments.
	 * @param array  $args      Parsed arguments.
	 * @param string $classname Class name based on current view, home or single.
	 * @param string $contents  HTML for site title or logo.
	 */
	$html = apply_filters( 'wpblank_site_logo', $html, $args, $classname, $contents );

	if ( ! $echo ) {
		return $html;
	}

	echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

}


function custom_logo() {
	$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="180" height="60.104" viewBox="0 0 180 60.104">
	  <g id="Group_1" data-name="Group 1" transform="translate(-382.362 -283.181)">
	    <path id="Path_1" data-name="Path 1" d="M453.741,319.749a2.949,2.949,0,0,1-1.451.4q-1.452,0-1.451-2.5V290.729H432.62v1.694a5.348,5.348,0,0,1,3.143,1.008q.968.845.968,3.183v16.121q0,6.693-1.814,10.439t-5.037,3.748a3.843,3.843,0,0,1-3.468-1.974,10.772,10.772,0,0,1-1.209-5.6V290.729h-17.33v1.694a4.265,4.265,0,0,1,2.377.886,3.152,3.152,0,0,1,.846,2.5V318.7q0,6.612,3.385,10.279T424,332.647a14.04,14.04,0,0,0,8.5-2.62,13.38,13.38,0,0,0,4.957-7.134h.16l2.1,10.156h1.291l15.233-12.736-.806-1.451Z" transform="translate(34.592 10.234)" fill="#fff"/>
	    <path id="Path_2" data-name="Path 2" d="M458.778,290.551a9.094,9.094,0,0,0-6.571,2.82,15.343,15.343,0,0,0-3.991,7.579h-.16l-.565-10.8h-1.449l-14.35,8.382v1.531q3.223,0,3.225,2.742V327.47a3.149,3.149,0,0,1-.848,2.5,4.265,4.265,0,0,1-2.377.886v1.692h22.975v-1.692a11.724,11.724,0,0,1-4.434-1.048,2.757,2.757,0,0,1-1.209-2.58V312.395a47.98,47.98,0,0,1,.806-10.439q.8-3.262,2.983-3.263a1.923,1.923,0,0,1,1.289.483,12.157,12.157,0,0,1,1.451,1.611,12.028,12.028,0,0,0,2.217,2.379,4.4,4.4,0,0,0,2.78.846,5.854,5.854,0,0,0,4.314-1.734A6.056,6.056,0,0,0,466.6,297.8a6.815,6.815,0,0,0-2.177-5.24A7.981,7.981,0,0,0,458.778,290.551Z" transform="translate(66.886 9.447)" fill="#fff"/>
	    <path id="Path_3" data-name="Path 3" d="M453.262,295.151a9.965,9.965,0,0,0,11.69,0,6.673,6.673,0,0,0,0-10.076,9.965,9.965,0,0,0-11.69,0,6.674,6.674,0,0,0,0,10.076Z" transform="translate(93.019)" fill="#fff"/>
	    <path id="Path_4" data-name="Path 4" d="M468.219,329.738a3.148,3.148,0,0,1-.846-2.5V290.319h-1.451L450.041,298.7v1.531q3.223,0,3.225,2.742v24.263a3.157,3.157,0,0,1-.846,2.5,4.281,4.281,0,0,1-2.38.886v1.692H470.6v-1.692A4.266,4.266,0,0,1,468.219,329.738Z" transform="translate(91.766 9.678)" fill="#fff"/>
	    <path id="Path_5" data-name="Path 5" d="M420.61,307.951l-5.567-6.907-13.577-16.849H382.845v1.854a4.087,4.087,0,0,1,3.185,1.291A4.415,4.415,0,0,1,387.2,290.4v36.757a22.058,22.058,0,0,1-1.291,8.425q-1.29,3.022-3.546,3.183v1.854h18.7v-1.854q-5.481-.318-8.382-3.023t-2.9-8.262V292.739l38.451,47.881h2.58V320.607l-2.58-3.2Z" transform="translate(0 1.374)" fill="#fff"/>
	    <path id="Path_6" data-name="Path 6" d="M397.041,286.048q5.481.325,8.382,3.023t2.9,8.262v12.72l2.58,3.2v-15.6a22.059,22.059,0,0,1,1.289-8.422q1.29-3.025,3.548-3.185v-1.854h-18.7Z" transform="translate(19.903 1.374)" fill="#fff"/>
	  </g>
	</svg>';
	$home = get_home_url();
	$site_title = get_bloginfo( 'name' );

	$html = '<a href="'. esc_url($home)  .'" class="custom-logo logo">'. $svg .'</a>';

	echo $html;
}
