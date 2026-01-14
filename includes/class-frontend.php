<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CODENIT_WC_Product_Image_Video_Frontend {

    public function __construct() {
        // Display video in WooCommerce gallery
        add_filter('woocommerce_single_product_image_thumbnail_html', [$this, 'display_video_in_gallery'], 10, 2);
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }
    
    /* =======================
     * Assets
     * ======================= */
    public function enqueue_assets() {
        if ( ! is_product() ) return;
    
        wp_enqueue_style(
            'codenit-wc-pvg',
            CODENITIVE_WCPV_URL . 'assets/css/codenit-video-gallery.css',
            [],
            '1.1.1'
        );
    
        wp_enqueue_script(
            'codenit-wc-pvg',
            CODENITIVE_WCPV_URL . 'assets/js/codenit-video-gallery.js',
            [ 'jquery' ],
            '1.1.8',
            true
        );
    
        // Pass PHP options to JS
        $video_settings = [
            'autoplay' => get_option('codenit_wc_video_autoplay', 'no') === 'yes',
            'loop'     => get_option('codenit_wc_video_loop', 'no') === 'yes',
            'overlay'  => get_option('codenit_wc_video_overlay', ''),
            'player'   => get_option('codenit_wc_video_player', 'youtube'),
        ];
    
        wp_localize_script('codenit-wc-pvg', 'codenitVideoSettings', $video_settings);
    }

    public function display_video_in_gallery($html, $attachment_id) {
        // Check if video functionality is enabled
        if (get_option('codenit_wc_video_enabled', 'yes') !== 'yes') return $html;
    
        // Device check
        $device = get_option('codenit_wc_video_device', 'all');
        $is_mobile = wp_is_mobile();
        if (($device === 'desktop' && $is_mobile) || ($device === 'mobile' && !$is_mobile)) return $html;
    
        // Get raw video URL
        $raw_video_url = get_post_meta($attachment_id, '_codenit_wc_video_url', true);
        if (!$raw_video_url) return $html;
    
        $autoplay = get_option('codenit_wc_video_autoplay', 'no') === 'yes';
        $loop     = get_option('codenit_wc_video_loop', 'no') === 'yes';
    
        // Check if it's a self-hosted MP4
        $extension = pathinfo($raw_video_url, PATHINFO_EXTENSION);
        if (strtolower($extension) === 'mp4') {
            return sprintf(
                '<div class="codenit-wc-product-video-wrapper">
                    <video controls %s %s muted playsinline preload="metadata">
                        <source src="%s" type="video/mp4">
                    </video>
                </div>',
                $autoplay ? 'autoplay' : '',
                $loop ? 'loop' : '',
                esc_url($raw_video_url)
            );
        }
    
        // For YouTube/Vimeo and other oEmbed-supported URLs
        $embed_url = $raw_video_url;
    
        // Build query params for autoplay/loop
        $query = [];
        if ($autoplay) $query['autoplay'] = 1;
        if ($loop) {
            $query['loop'] = 1;
            // For YouTube, loop requires playlist param
            if (strpos($embed_url, 'youtube.com') !== false || strpos($embed_url, 'youtu.be') !== false) {
                $video_id = wp_parse_url($embed_url, PHP_URL_QUERY);
                parse_str($video_id, $video_params);
                $video_id = $video_params['v'] ?? '';
                if ($video_id) $query['playlist'] = $video_id;
            }
        }
    
        if (!empty($query)) {
            $embed_url = add_query_arg($query, $embed_url);
        }
    
        // Get oEmbed HTML
        $iframe_html = wp_oembed_get($embed_url, ['width' => 640, 'height' => 360]);
        if ($iframe_html) {
            return '<div class="codenit-wc-product-video-wrapper codenit-wc-embed-video">' . $iframe_html . '</div>';
        }
    
        // Fallback: return original image
        return $html;
    }
    
}
