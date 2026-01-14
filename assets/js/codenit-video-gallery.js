jQuery(function ($) {

    function codenit_playActiveVideo() {

        // Use the localized settings
        if (!codenitVideoSettings.autoplay) return;
    
        var $activeSlide =
            $('.woocommerce-product-gallery .slick-active, \
               .woocommerce-product-gallery .flex-active-slide, \
               .wc-block-product-gallery .swiper-slide-active')
            .first();
    
        if (!$activeSlide.length) return;
    
        // HTML5 Video
        var video = $activeSlide.find('video').get(0);
        if (video) {
            video.play().catch(function () {});
            return;
        }
    
        // YouTube / Vimeo iframe
        var iframe = $activeSlide.find('iframe');
    
        if (iframe.length) {
            var src = iframe.attr('src').replace(/([?&])(autoplay|mute)=\d+/g, '');
            iframe.attr('src', src + (src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1&mute=1');
        }
        
    }


    function codenit_stopAllVideos() {
        $('video').each(function () {
            this.pause();
            this.currentTime = 0;
        });
    
        $('iframe').each(function () {
            var src = $(this).attr('src');
            if (src) $(this).attr('src', src);
        });
    }

    /* =======================
     * WooCommerce Classic Gallery (FlexSlider)
     * ======================= */
    $('.woocommerce-product-gallery').on('afterChange', function () {
        codenit_stopAllVideos();
        codenit_playActiveVideo();
    });

    $(window).on('load', function () {
        codenit_playActiveVideo();
    });

    document.addEventListener('click', function (e) {
        if (
            e.target.closest('.wc-block-product-gallery') ||
            e.target.closest('.wc-block-product-gallery__thumbnail')
        ) {
            setTimeout(function () {
                codenit_stopAllVideos();
                codenit_playActiveVideo();
            }, 100);
        }
    });


});
