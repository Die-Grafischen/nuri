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

		var cover = $('.wrapper > article > div').first();

 		cover.addClass('cover-on-top').css({ height: window.innerHeight });

		cover.find('.wp-block-cover__inner-container').append('<div class="scrollToBottom bounce"><a href="#"><i class="scroll-icon"></i></a></div>');

		$('.wp-block-cover').on('click','.scrollToBottom', function(){
			console.log('scr');
			var dest = cover.next().offset().top;
			$('html,body').animate({
                scrollTop: dest
            }, 1000, 'swing');
		});

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
	var container = $('.products');

	// PROJECTS/HOME ISOTOPE
	if ($('.woo-custom-filter').length) {

		var filter = '*';
        if(window.location.hash) {
            filter = '.' + window.location.hash.substr(1);
            $('#filters .button.is-checked').removeClass('is-checked');
            $('#filters .button.bt-' + window.location.hash.substr(1)).addClass('is-checked');
        }


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

	/******** END ISOTOPE AJAX ********/



		var pull_page = 1;

		$('#ajax-load-more-products').on('click', function(){

		    var jsonFlag = true;
		    if(jsonFlag){

		    jsonFlag = false;
		    pull_page++;
		    $.getJSON("https://localhost:3000/wp-json/products/all?page=" + pull_page, function(data){

		    if(data.length){

		        var items = [];
		        $.each(data, function(key, val){
		           	var arr = $.map(val, function(el) { return el; });

				  	var id = arr[0];
					var title = arr[1];
					var price = arr[2];
					var terms = arr[3];
					var image = arr[4];

		            var item_string = '<li class="product type-product post-'+ id +' status-publish '+ terms +'has-post-thumbnail shipping-taxable purchasable product-type-simple"><a href="#" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">'+ image +'<h2 class="woocommerce-loop-product__title">'+ title +'</h2><span class="price"><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">CHF</span>'+ price +'</bdi></span></span></a></li>';

		            items.push(item_string);
		        });
		        if(data.length >= 4){

		            $('.products').append(items);
					container.isotope('reLayout');


		        } else {

		            $('.products').append(items);
					container.isotope('reLayout');
		            $('.load-more-wrapper').hide();

		        }

		    } else {

		        $('.load-more-wrapper').hide();

		    }

		    }).done(function(data){
		        if(data.length){ jsonFlag = true; }
		    });}
		});




}); // END jQuery
