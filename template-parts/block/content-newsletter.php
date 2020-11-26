<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$text = get_field('text');
$bgr = get_field('hintergrundbild');
$mailchimp = get_field('mailchimp');

echo '<div class="block-newsletter acf-block" style="background-image: url('. esc_url($bgr) .');">
	<div class="newsletter-wrapper">
		<div class="newsletter-text">
			'. wp_kses_post($text) .'
		</div>
		<form>
			<input type="text">
			<input type="submit" value="Registrieren">
		</form>
	</div>
</div>';

?>
