import './bootstrap';
import '@iconify/iconify';

// Import Fancybox CSS and JS
import '@fancyapps/fancybox/dist/jquery.fancybox.css';
import '@fancyapps/fancybox';

// Initialize Fancybox (Optional)
$(document).ready(function() {
  $('[data-fancybox="gallery"]').fancybox({
    buttons: ["zoom", "slideShow", "fullScreen", "thumbs", "close"],
    caption: function(instance, item) {
      return $(this).find('img').attr('alt');
    }
  });
});
