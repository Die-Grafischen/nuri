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

		<!-- Begin Mailchimp Signup Form -->
		<form action="https://nurifood.us7.list-manage.com/subscribe/post?u=849dec97a1d3a7ba1aeb5f440&amp;id=defed08fa3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
		    <div id="mc_embed_signup_scroll">

		<div class="mc-field-group">
			<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
		</div>

		<div id="mce-responses" class="clear">
				<div class="response" id="mce-error-response" style="display:none"></div>
				<div class="response" id="mce-success-response" style="display:none"></div>
			</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
		    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_849dec97a1d3a7ba1aeb5f440_defed08fa3" tabindex="-1" value=""></div>
		    <div class="clear"><input type="submit" value="Registrieren" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
		    </div>
		</form>
		<!--End mc_embed_signup-->

	</div>
</div>';

?>
