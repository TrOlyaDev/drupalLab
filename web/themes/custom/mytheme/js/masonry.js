(function ($, Drupal, drupalSettings) {

  'use strict';

  Drupal.behaviors.my_theme_masonry= {
    attach: function (context, settings) {
      $('.grid', context).masonry({
        // options
        itemSelector: '.grid-item',
        columnWidth: 205
      });
    },
  };

})(jQuery, Drupal, drupalSettings);
