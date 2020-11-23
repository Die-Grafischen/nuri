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
  }
})(jQuery);