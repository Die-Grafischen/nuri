"use strict";

/**
 * Custom JavaScript
 *
 * @since 1.0.0
 */
var nuriTheme;
jQuery(document).ready(function ($) {
  'use strict';

  $('#nav-toggle').on('click', function () {
    $('body').toggleClass('active-nav');
    $('#nav-toggle').toggleClass('active');
  });
  $('#mobile-filter').on('click', function () {
    $('body').toggleClass('active-filter');
    $('#mobile-filter-icon').toggleClass('active-mobile-filter');
    $('#mobile-filter + ul').slideToggle();
  }); // Window Scroll

  var header = $('header');
  var theWindow = $(window);

  if ($('.wrapper > article > div').first().hasClass('wp-block-cover')) {
    var cover = $('.wrapper > article > div').first();
    cover.addClass('cover-on-top').css({
      height: window.innerHeight
    });
    cover.find('.wp-block-cover__inner-container').append('<div class="scrollToBottom bounce"><a href="#"><i class="scroll-icon"></i></a></div>');
    $('.wp-block-cover').on('click', '.scrollToBottom', function () {
      var dest = cover.next().offset().top;
      $('html,body').animate({
        scrollTop: dest
      }, 1000, 'swing');
    });
    header.addClass('dynamic-header');
    theWindow.scroll(function () {
      if (theWindow.scrollTop() >= theWindow.height()) {
        header.addClass('fixed-header');
      } else {
        header.removeClass('fixed-header');
      }
    });
  } // toggle zusätzliche info


  $('.single-product-info').on('click', '.single-product-info-title', function () {
    $(this).next().slideToggle();
  }); // move category back link inside gallery

  if ($('.woo-back').length) {
    var wooBack = $('.woo-back').detach();
    $('.woocommerce-product-gallery').prepend(wooBack);
    $('.woo-back').css('opacity', 1);
    $('.woo-back-link').hover(function () {
      $('.woo-back-name').fadeToggle();
    }); // wooBack;
  }
  /******** ISOTOPE AJAX ********/


  var loadedProductsIds = []; //loaded products ids

  var queryCategories = []; // current query categories id

  var filter = '*'; // default filter for isotope, select all categories
  // SHOP ISOTOPE - runns only on woo shop/category/tags page

  if ($('body:not(.search) .woo-custom-filter').length) {
    // Resets isotope filter and hides subcategories
    var formReset = function formReset() {
      queryCategories = [];
      parentCategory = '';
      $('input[type=checkbox]').prop('checked', false);
      container.isotope({
        filter: filter
      });
      $('.filter-current-parent .filter-child-cat').slideUp();
      $('.filter-current-parent').removeClass('filter-current-parent');
    }; // Triggers filter reset on reset link click


    // this function runs every time you are scrolling
    var isInViewport = function isInViewport(el) {
      var elementTop = $(el).offset().top;
      var elementBottom = elementTop + $(el).outerHeight();
      var viewportTop = $(window).scrollTop();
      var viewportBottom = viewportTop + $(window).height();

      if (elementBottom > viewportTop && elementTop < viewportBottom && jsonFlag) {
        //make rest requerst
        loadMoreProducts(); //jsonFlag = false;
      }
    };

    // hide/reveal subnav filter on scroll
    var lastScrollTop = 0;
    theWindow.scroll(function (event) {
      var st = $(this).scrollTop();

      if (st > lastScrollTop) {
        // downscroll
        if (theWindow.width > 480) {
          $('.filter-current-parent .filter-child-cat').slideUp().addClass('sub-visible').removeClass('sub-hidden');
        }
      } else {
        // upscroll code
        if (theWindow.width > 480) {
          $('.filter-current-parent .filter-child-cat').slideDown().addClass('sub-hidden').removeClass('sub-visible');
        }
      }

      lastScrollTop = st;
    }); // show subnav filter on mouse hover

    $(document).on('mouseenter', '.woo-custom-filter', function (event) {
      $('.filter-current-parent .filter-child-cat').slideDown();
    }).on('mouseleave', '.woo-custom-filter', function () {
      $('.filter-current-parent .filter-child-cat.sub-visible').slideUp();
    }); // Only on category page

    if ($('.woo-custom-filter').data('query-cat-id')) {
      var id = $('.woo-custom-filter').data('query-cat-id');
      queryCategories.push(id); // add parent category to current query categories

      filter = '.product_cat-' + id;
      console.log('category page');
    }

    $('.filter-current-parent .filter-child-cat').slideToggle();
    var container = $('.products'); // get classes of all loaded products and save product ids in loadedProductsIds[];

    container.children().each(function () {
      var clas = $(this).attr('class').split("post-")[1].match(/\d+/)[0]; // stripe only number(product id)

      loadedProductsIds.push(clas);
    }); // Woo Filter - filter products with isotope.js on parent category click

    $('.filter-parent-cat').on('click', 'span', function (e) {
      $("html, body").animate({
        scrollTop: 0
      }, "slow");
      formReset();
      var parent = $(this).parent();

      if (parent.find('.filter-child-cat').css('display') == 'block') {
        $('.filter-current-parent .filter-child-cat').slideToggle().parent().removeClass('filter-current-parent');
      } else if ($('.filter-child-cat').css('display') == 'block') {
        $('.filter-current-parent .filter-child-cat').slideToggle();
        $('.filter-current-parent').removeClass('filter-current-parent');
        parent.addClass('filter-current-parent').find('.filter-child-cat').slideToggle();
      } else {
        parent.addClass('filter-current-parent').find('.filter-child-cat').slideToggle();
      }
    });
    $('.woo-custom-filter').on('click', '.clear-filter', function (e) {
      formReset();
      console.log('clicked on form reset link');
      console.log('jsonFlag: ' + jsonFlag);
      var elements = container.isotope('getFilteredItemElements').length;

      if (elements % 4 === 0) {
        loadMoreProducts();
      } else {
        loadMoreProducts(4 - elements % 4);
      }
    });

    if ($('.filter-child-cat li input.checked').length) {
      $('.filter-child-cat li input.checked').trigger('click');
    } // init Isotope


    container.isotope({
      itemSelector: '.product',
      layoutMode: 'fitRows',
      filter: filter
    }); // bind filter button click

    var parentCategory;
    $('.woo-custom-filter').on('click', '.product-parent-selector', function () {
      var filterValue = $(this).attr('data-filter');
      parentCategory = filterValue.substring(13);
      queryCategories.push(parentCategory); // add parent category to current query categories

      container.isotope({
        filter: filterValue
      }); // count of visible filtered elements

      var elements = container.isotope('getFilteredItemElements').length;

      if (elements % 4 === 0) {
        loadMoreProducts();
      } else {
        loadMoreProducts(4 - elements % 4);
      }
    }); // filter products on subcategories(checkbox) click

    var checkboxes = $('.filter-child-cat input');
    checkboxes.change(function () {
      var filters = [];
      queryCategories = []; // get checked checkboxes values

      checkboxes.filter(':checked').each(function () {
        $("html, body").animate({
          scrollTop: 0
        }, "slow");
        var filterValue = $(this).attr('data-filter');
        filters.push(filterValue);
        queryCategories.push(filterValue.substring(13)); // add parent category to current query categories
      });

      if (queryCategories.length == 0) {
        queryCategories.push(parentCategory);
      }

      filters = filters.join(', '); // join array into one string

      console.log('filters: ' + filters);
      container.isotope({
        filter: filters
      });
    });
    ; // check if the end of the product list is visible

    $(window).on('resize scroll', function () {
      isInViewport('.load-more-wrapper');
    });
    setTimeout(function () {
      loadMoreProducts();
    }, 100);
  }
  /******** END ISOTOPE AJAX ********/
  // production enviroment - read only permissions


  var wooClientKey = 'ck_4dbc36409a2f3772d2bb18e8b066f31c20f1cdde';
  var wooClientSecret = 'cs_b9046328ab21f1aa53715a6c37cf455f35ffdaab';
  var wooUrl = 'https://nurifood.ch/wp-json/wc/v3/products'; // dev enviroment
  // const wooClientKey = 'ck_d3075b8231bedc08b740a91d77a6fe28b34e6df2';
  // const wooClientSecret = 'cs_b2f087b724e028bbd46fb68879555009941e247e';
  // const wooUrl = 'https://nuri.local/wp-json/wc/v3/products';
  // authorization

  function basicAuth(key, secret) {
    var hash = btoa(key + ':' + secret);
    return "Basic " + hash;
  }

  var auth = basicAuth(wooClientKey, wooClientSecret);
  var pull_page = 1;
  var jsonFlag = true;
  var postsPerPage = $('.woo-custom-filter').data('postcount') ? $('.woo-custom-filter').data('postcount') : 12; // ajax get request to woo rest api

  function getData(url) {
    jQuery.ajax({
      url: url,
      method: 'GET',
      beforeSend: function beforeSend(req) {
        req.setRequestHeader('Authorization', auth);
        jsonFlag = false;
      },
      success: function success(data) {
        var queryLength = 3;
        var currentQuery = 0;
        console.log('data length: ' + data.length);

        if (data.length <= queryLength) {
          $('.lds-ellipsis').fadeOut();
          jsonFlag = true;
        } else {
          $('.lds-ellipsis').fadeIn();
        }

        jQuery.each(data, function (index, item) {
          if (currentQuery <= queryLength) {
            var _id = data[index].id;
            var title = data[index].name;
            var permalink = data[index].permalink;
            var price = data[index].price_html;
            var terms = data[index].categories;
            var categories = '';
            terms.forEach(function (item, index, array) {
              categories = categories + 'product_cat-' + item.id + ' ';
            });
            var imageSrc = data[index].images[0] ? data[index].images[0].shop_catalog : 'https://nurifood.ch/wprs/wp-content/uploads/custom-woo-placeholder.gif';
            var productHtml = $('<li class="product type-product post-' + _id + ' status-publish ' + categories + 'has-post-thumbnail shipping-taxable purchasable product-type-simple"><a href="' + permalink + '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link"><img width="315" height="420" src="' + imageSrc + '" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt loading="lazy" /><h2 class="woocommerce-loop-product__title">' + title + '</h2><span class="price">' + price + '</span></a></li>');
            container.isotope('insert', productHtml); //insert new product to isotope

            loadedProductsIds.push(_id); // add loaded products id to the exeptions for future queries

            currentQuery++;
          }
        });
      },
      error: function error(XMLHttpRequest, textStatus, errorThrown) {
        console.log("ERROR : ", errorThrown);
        console.log("ERROR : ", $xhr);
        console.log("ERROR : ", textStatus);
      }
    }).done(function (data) {
      if (data.length) {
        jsonFlag = true;
      }

      console.log('done request'); //container.layout(); //relayout isotope
    });
  } //load more products and make ajax call


  function loadMoreProducts() {
    var productCount = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 4;
    var tempCount = productCount;
    console.log('jsonFlag:' + jsonFlag);

    if (jsonFlag) {
      var catString = queryCategories.join(',');
      var excString = loadedProductsIds.join(',');
      console.log(wooUrl + '?per_page=' + productCount + '&category=' + catString + '&exclude=' + excString);
      getData(wooUrl + '?per_page=' + productCount + '&category=' + catString + '&exclude=' + excString);
    } else {
      console.log('wait. loading from rest');
      setTimeout(function () {
        loadMoreProducts(tempCount);
      }, 200);
    }
  } // search overlay


  $('#search-icon').on('click', function () {
    $('#search-overlay').fadeToggle();
    $('.aws-search-field').focus();
  });
  $('#search-overlay').on('click', function (e) {
    if (e.target != this) return;
    $('#search-overlay').fadeToggle();
  });
  $(document).keyup(function (e) {
    if (e.keyCode === 27) $('#search-overlay').fadeOut();
  });
  $('.search-results .woo-custom-filter, .search-results .load-more-wrapper').remove(); // style select fields

  if ($('.woocommerce .wrapper select').length) {
    $('select').selectWoo();
  }
}); // END jQuery