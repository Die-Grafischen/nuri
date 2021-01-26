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

	// style select fields
	if ( $('select').length ){
		$('select').selectWoo();
	}

	/******** ISOTOPE AJAX ********/

	const loadedProductsIds = new Array(); //save all loaded in dom products id in array

	// PROJECTS/HOME ISOTOPE
	if ($('.woo-custom-filter').length) {

		var container = $('.products');

		//get classes of all loaded products
		container.children().each(function(){
	        let clas = ($(this).attr('class')).split("post-")[1].match(/\d+/)[0];  // stripe only number(product id)
			loadedProductsIds.push(clas);
	    });

		// Woo Filter
		$('.filter-parent-cat').on('click', 'span', function(e){
			$("html, body").animate({ scrollTop: 0 }, "slow");
			formReset();

			var parent = $(this).parent();

			if( parent.find('.filter-child-cat').css('display') == 'block' ) {
				$('.filter-current-parent .filter-child-cat').slideToggle().parent().removeClass('filter-current-parent');
			}
			else if( $('.filter-child-cat').css('display') == 'block' ) {
				$('.filter-current-parent .filter-child-cat').slideToggle();
				$('.filter-current-parent').removeClass('filter-current-parent');
				parent.addClass('filter-current-parent').find('.filter-child-cat').slideToggle();
			} else {
				parent.addClass('filter-current-parent').find('.filter-child-cat').slideToggle();
			}

		});

		function formReset() {

			queryCategories = [];
			parentCategory = '';

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
		let parentCategory;
        $('.woo-custom-filter').on('click', '.product-parent-selector', function() {
            var filterValue = $(this).attr('data-filter');
			parentCategory = filterValue.substring(13);
			queryCategories.push(parentCategory); // add parent category to current query categories
            container.isotope({
                filter: filterValue
            });

			var elements = container.isotope('getFilteredItemElements');
			if(elements < postsPerPage) {
				loadMoreProducts();
			}

        });

		var checkboxes = $('.filter-child-cat input');
		checkboxes.change(function(){

			var filters = [];
			queryCategories = [];

			// get checked checkboxes values
			checkboxes.filter(':checked').each(function(){
				$("html, body").animate({ scrollTop: 0 }, "slow");

				let filterValue = $(this).attr('data-filter');
			 	filters.push(filterValue);
			 	queryCategories.push(filterValue.substring(13)); // add parent category to current query categories
			});

			if (queryCategories.length == 0) {
				queryCategories.push(parentCategory);
			}

			filters = filters.join(', '); // join array into one string

			container.isotope({ filter: filters });
		 });

		 // this function runs every time you are scrolling
 		function isInViewport(el) {
 		    var elementTop = $(el).offset().top;
 		    var elementBottom = elementTop + $(el).outerHeight();

 		    var viewportTop = $(window).scrollTop();
 		    var viewportBottom = viewportTop + $(window).height();

 		   if( (elementBottom > viewportTop && elementTop < viewportBottom) && jsonFlag ) {
 			   jsonFlag = false;
 			  //make rest requerst
 			  loadMoreProducts();
 		   }
 		};

 		// check if the end of the product list is visible
 		$(window).on('resize scroll load', function() {
 		    isInViewport('.load-more-wrapper');
 		});

	}

	/******** END ISOTOPE AJAX ********/

		// production enviroment
		const wooClientKey = 'ck_4dbc36409a2f3772d2bb18e8b066f31c20f1cdde';
		const wooClientSecret = 'cs_b9046328ab21f1aa53715a6c37cf455f35ffdaab';
		const wooUrl = 'https://nurifood.ch/wp-json/wc/v3/products';

		// dev enviroment
		// const wooClientKey = 'ck_d3075b8231bedc08b740a91d77a6fe28b34e6df2';
		// const wooClientSecret = 'cs_b2f087b724e028bbd46fb68879555009941e247e';
		// const wooUrl = 'https://nuri.local/wp-json/wc/v3/products';


		function basicAuth(key, secret) {
		    let hash = btoa(key + ':' + secret);
		    return "Basic " + hash;
		}

		let auth = basicAuth(wooClientKey, wooClientSecret);

		var pull_page = 1;
		var jsonFlag = true;

		let postsPerPage = $('.woo-custom-filter').data('postcount') ? $('.woo-custom-filter').data('postcount') : 12;

		let queryCategories = new Array(); //current query categories

		function getData(url) {
		    jQuery.ajax({
		        url: url,
		        method: 'GET',
		        beforeSend: function (req) {
		            req.setRequestHeader('Authorization', auth);
					jsonFlag = false;
		        },
				success: function(data) {
					let queryLength = 3;
					let currentQuery = 0;
					console.log('data length: ' + data.length);
					if ( data.length <= queryLength ) {
						$('.lds-ellipsis').fadeOut();
					} else {
						$('.lds-ellipsis').fadeIn();
					}
			        jQuery.each(data, function(index, item) {
						if ( currentQuery <= queryLength ) {

							let id = data[index].id;
							let title = data[index].name;
							let permalink = data[index].permalink;
							let price = data[index].price_html;
							let terms = data[index].categories;
							let categories = '';

							terms.forEach(function(item, index, array) {
							  categories = categories + 'product_cat-' + item.id + ' ';

						  	});

							let imageSrc = data[index].images[0] ? data[index].images[0].woocommerce_single : 'https://nurifood.ch/wprs/wp-content/uploads/woocommerce-placeholder-600x600.png' ;


							let productHtml = $('<li class="product type-product post-'+ id +' status-publish '+ categories +'has-post-thumbnail shipping-taxable purchasable product-type-simple"><a href="'+ permalink +'" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="300" height="300" src="'+ imageSrc +'" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt loading="lazy" /><h2 class="woocommerce-loop-product__title">'+ title +'</h2><span class="price">'+ price +'</span></a></li>');

							container.isotope( 'insert', productHtml ); //insert new product to isotope

							loadedProductsIds.push(id);

							currentQuery++;

						}

			        });
			    },
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log("ERROR : ", errorThrown);
					console.log("ERROR : ", $xhr);
					console.log("ERROR : ", textStatus);
			    }
		    })
		        .done(function (data) {
					if(data.length){ jsonFlag = true; }
		        });
		}

		//load more products and make ajax call
		function loadMoreProducts() {
			let catString = queryCategories.join(',');
			let excString = loadedProductsIds.join(',');
			console.log(wooUrl+'?per_page='+postsPerPage+'&category='+catString+'&exclude='+excString);

			getData(wooUrl+'?per_page='+postsPerPage+'&category='+catString+'&exclude='+excString);
		}








}); // END jQuery
