const mix = require('laravel-mix');


// mix.js([
//     'resources/js/frontend/default/app.js',
//     'backend/js/loadah.min.js',
//     'frontend/default/js/jquery.simpleLoadMore.js',
//     'backend/vendors/select2/js/select2.min.js',
//     'backend/vendors/js/nice-select.min.js',
//     'frontend/default/vendors/owl_carousel/js/owl.carousel.min.js',
//     'frontend/default/js/jquery.nicescroll.min.js',
//     'frontend/default/js/rangeslider.js',
//     'frontend/default/js/jquery.scrollbar.min.js',
//     'frontend/default/vendors/gijgo/gijgo.min.js',
//     'frontend/default/vendors/responsive_table/js/tablesaw.stackonly.js',
//     'frontend/default/js/custom.js',
//     'frontend/default/js/highlight.js',
//     'backend/vendors/lazyload/lazyload.js'
// ], 'frontend/default/compile_js/app.js')

// .sass('resources/sass/frontend/default/app.scss', 'frontend/default/compile_css/app.css').options({
//     processCssUrls: false
// })
// .sass('resources/sass/frontend/default/rtl_app.scss', 'frontend/default/compile_css/rtl_app.css').options({
//     processCssUrls: false
// });

mix.js([
    'resources/js/frontend/amazy/app.js',
    'public/backend/js/loadah.min.js',
    'public/frontend/amazy/js/owl.carousel.min.js',
    'public/frontend/amazy/js/isotope.pkgd.min.js',
    'public/frontend/amazy/js/waypoints.min.js',
    'public/frontend/amazy/js/jquery.counterup.min.js',
    'public/frontend/amazy/js/imagesloaded.pkgd.min.js',
    'public/frontend/amazy/js/nice-select.min.js',
    'public/frontend/amazy/js/barfiller.js',
    'public/frontend/amazy/js/jquery.slicknav.js',
    'public/frontend/amazy/js/jquery.magnific-popup.min.js',
    'public/frontend/amazy/js/jquery.ajaxchimp.min.js',
    'public/frontend/amazy/js/parallax.js',
    'public/frontend/amazy/js/gijgo.min.js',
    'public/frontend/amazy/js/slick.min.js',
    'public/frontend/amazy/js/perfect-scrollbar.js',
    'public/frontend/amazy/js/jquery.nav.js',
    'public/frontend/amazy/js/summernote-lite.min.js',
    'public/frontend/amazy/js/query-ui.js',
    'public/frontend/amazy/js/jquery.countdown.min.js',
    'public/frontend/amazy/js/mail-script.js',
    'public/frontend/amazy/js/main.js',
    'public/frontend/default/js/highlight.js',
    'public/backend/vendors/lazyload/lazyload.js'
], 'public/frontend/amazy/compile_js/app.js')

.sass('resources/sass/frontend/amazy/app.scss', 'public/frontend/amazy/compile_css/app.css').options({
        processCssUrls: false
    })
    .sass('resources/sass/frontend/amazy/rtl_app.scss', 'public/frontend/amazy/compile_css/rtl_app.css').options({
        processCssUrls: false
    });