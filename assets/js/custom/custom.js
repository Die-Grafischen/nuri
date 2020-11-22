/**
 * Custom JavaScript
 *
 * @since 1.0.0
 */


var nuriTheme;

(function ($) {
	'use strict';

	// Window Scroll
	var header = $('header');

	var headerOffset = $('header').offset();

	$(window).scroll(function() {

	    if ( $('body').scrollTop() > headerOffset.top){
	        header.removeClass('fixed');
			console.log('>');
	    } else {
	        header.addClass('fixed');
			console.log('<');
	    }


	});

})(jQuery);
