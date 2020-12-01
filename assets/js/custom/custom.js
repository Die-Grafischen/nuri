/**
 * Custom JavaScript
 *
 * @since 1.0.0
 */


var nuriTheme;

jQuery(document).ready(function($) {
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
		formReset();

		var th = this;
		var parent = $(th).parent();
		if( parent.hasClass('filter-current-parent') ){
			$('.filter-current-parent .filter-child-cat').slideToggle().parent().removeClass('filter-current-parent');
		}
		else if($('.filter-current-parent').length) {
			$('.filter-current-parent .filter-child-cat').slideToggle();
			$('.filter-current-parent').removeClass('filter-current-parent');
			parent.addClass('filter-current-parent');
			parent.find('.filter-child-cat').slideToggle();

		} else {
			parent.addClass('filter-current-parent');
			parent.find('.filter-child-cat').slideToggle();
		}


	});

	function formReset() {
		$('input[type=checkbox]').prop('checked',false);
		container.isotope({
			filter: filter
		});
		$('.filter-current-parent .filter-child-cat').slideToggle();
		$('.filter-current-parent').removeClass('filter-current-parent');
	}

	$('.woo-custom-filter').on('click', '.clear-filter', function(e){
		formReset();
	});

	/******** ISOTOPE AJAX ********/

	// PROJECTS/HOME ISOTOPE
	if ($('.woo-custom-filter').length) {

		var filter = '*';
        if(window.location.hash) {
            filter = '.' + window.location.hash.substr(1);
            $('#filters .button.is-checked').removeClass('is-checked');
            $('#filters .button.bt-' + window.location.hash.substr(1)).addClass('is-checked');
        }

		var container = $('.products');

		// init Isotope
		container.isotope({
			itemSelector: '.product',
			layoutMode: 'fitRows',
            filter: filter
		});

		// bind filter button click
        $('.woo-custom-filter').on('click', '.product-parent-selector', function() {
            var filterValue = $(this).attr('data-filter');
            container.isotope({
                filter: filterValue
            });
        });

		var checkboxes = $('.filter-child-cat input');
		checkboxes.change(function(){
			var filters = [];
			// get checked checkboxes values
			checkboxes.filter(':checked').each(function(){
			 filters.push( $(this).attr('data-filter') );
			});
			// join array into one string
			filters = filters.join(', ');
			container.isotope({ filter: filters });
		 });




	}



}); // END jQuery
