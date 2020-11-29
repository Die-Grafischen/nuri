"use strict";

/**
 * Custom JavaScript
 *
 * @since 1.0.0
 */
var sub = function sub(a, b) {
  return a - b;
};
"use strict";

/**
 * Custom JavaScript
 *
 * @since 1.0.0
 */
var nuriTheme;

(function ($) {
  'use strict'; // Window Scroll

  var header = $('header');
  var theWindow = $(window);

  if ($('.wrapper > article > div').first().hasClass('wp-block-cover')) {
    $('.wrapper > article > div').first().addClass('cover-on-top');
    header.addClass('dynamic-header');
    theWindow.scroll(function () {
      if (theWindow.scrollTop() >= theWindow.height()) {
        header.addClass('fixed-header');
      } else {
        header.removeClass('fixed-header');
      }
    });
  } // Woo Filter


  $('.filter-parent-cat').on('click', 'span', function (e) {
    var th = this;
    var parent = $(th).parent();

    if (parent.hasClass('filter-current-parent')) {
      $('.filter-current-parent .filter-child-cat').slideToggle().parent().removeClass('filter-current-parent');
    } else if ($('.filter-current-parent').length) {
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
    $('input[type=checkbox]').prop('checked', false);
  }

  $('.woo-custom-filter').on('click', '.clear-filter', function (e) {
    formReset();
  });
  /******** ISOTOPE AJAX ********/

  jQuery(document).ready(function ($) {
    //ISOTOPE HELPER FUNCTIONS AND VARIABLES
    function loadMore(toShow) {
      $container.find(".hidden").removeClass("hidden");
      var hiddenElems = iso.filteredItems.slice(toShow, iso.filteredItems.length).map(function (item) {
        return item.element;
      });
      $(hiddenElems).addClass('hidden');
      $container.isotope('layout'); //when no more to load, hide show more button

      if (hiddenElems.length == 0) {
        jQuery("#load-more").hide();
      } else {
        jQuery("#load-more").show();
      }
    } //when load more button clicked


    $("#load-more").click(function () {
      if ($('#filters').data('clicked')) {
        //when filter button clicked, set initial value for counter
        counter = initShow;
        $('#filters').data('clicked', false);
      } else {
        counter = counter;
      }

      counter = counter + initShow;
      loadMore(counter);
    }); //when filter button clicked

    $("#filters").click(function () {
      $(this).data('clicked', true);
      loadMore(initShow);
    });
    var initShow = 12; //number of items loaded on init & onclick load more button

    if (window.innerWidth > 1690) {
      initShow = 15;
    }

    var counter = initShow; //counter for load more button

    var iso;
    var container; //END ISOTOPE HELPER
    // PROJECTS/HOME ISOTOPE

    if ($('.products').length) {
      var filter = '*';

      if (window.location.hash) {
        filter = '.' + window.location.hash.substr(1);
        $('#filters .button.is-checked').removeClass('is-checked');
        $('#filters .button.bt-' + window.location.hash.substr(1)).addClass('is-checked');
      } // init Isotope


      $container = $('.products').isotope({
        itemSelector: '.element-item',
        gutter: '.gutter-sizer',
        layoutMode: 'fitRows',
        getSortData: {
          category: '[data-category]'
        },
        filter: filter
      }); // bind filter button click

      $('#filters').on('click', '.button', function () {
        var filterValue = $(this).attr('data-filter');
        $container.isotope({
          filter: filterValue
        });
      }); // change is-checked class on buttons

      $('.button-group').each(function (i, buttonGroup) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.on('click', '.button', function () {
          $buttonGroup.find('.is-checked').removeClass('is-checked');
          $(this).addClass('is-checked');
        });
      }); //****************************
      // Isotope Load more button
      //****************************

      iso = $container.data('isotope'); // get Isotope instance

      loadMore(initShow); //execute function onload
      //append load more button

      $container.after('<div class="load-more-wrapper"><div id="load-more">Mehr Projekte</div></div>'); //when load more button clicked

      $("#load-more").click(function () {
        console.log('load-more clicked');

        if ($('#filters').data('clicked')) {
          //when filter button clicked, set initial value for counter
          counter = initShow;
          $('#filters').data('clicked', false);
        } else {
          counter = counter;
        }

        counter = counter + initShow;
        loadMore(counter);
      });
    } //END ISOTOPE

  });
})(jQuery);