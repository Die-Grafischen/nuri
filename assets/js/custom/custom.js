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
	var theWindow = $(window);

	if( $('.wrapper > article > div').first().hasClass('wp-block-cover') ){
 		$('.wrapper > article > div').first().addClass('cover-on-top');
		header.addClass('dynamic-header');
		theWindow.scroll(function(){
		    if (theWindow.scrollTop() >= theWindow.height()) {
		        header.addClass('fixed-header');
		    }
		    else {
		        header.removeClass('fixed-header');
		    }
		});
	}

	// Woo Filter
	$('.filter-parent-cat').on('click', 'span', function(e){
		var th = this;
		if($('.filter-current-parent').length) {
			$('.filter-child-cat').slideToggle().removeClass('filter-current-parent');
		} else {
			$(th).parent().addClass('filter-current-parent');
			$(th).parent().find('.filter-child-cat').slideToggle();
		}

	});


})(jQuery);
