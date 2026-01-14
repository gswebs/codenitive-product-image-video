<?php
if (!defined('ABSPATH')) exit;

add_filter('woocommerce_single_product_image_thumbnail_html', function($html, $attachment_id) {
    
    $overlay_icon = get_option('codenit_wc_video_overlay', '');
    // Check if this attachment has a video URL
    $video_url = get_post_meta($attachment_id, '_codenit_wc_video_url', true);
    if (!$video_url) return $html; // no video, return normal thumbnail

    $play_icon_default = 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
          <circle cx="32" cy="32" r="32" fill="rgba(0,0,0,0.6)"/>
          <polygon points="24,16 24,48 48,32" fill="#fff"/>
        </svg>
    ');

    $play_icon = !empty($overlay_icon) ? $overlay_icon : $play_icon_default;

    // Wrap thumbnail in div and add play button overlay
    $html = '<div class="codenit-wc-video-thumb" data-video-url="' . esc_url($video_url) . '">
                ' . $html . '
                <span class="codenit-wc-video-play" style="background:url(' . $play_icon . ') no-repeat center center;"></span>
            </div>';

    return $html;

}, 10, 2);


function codenit_get_embed_data( $url, $autoplay = false, $loop = false ) {

    if ( empty( $url ) ) {
        return false;
    }

    $params = [];

    if ( $autoplay ) {
        $params[] = 'autoplay=1';
        $params[] = 'mute=1';
    }

    if ( $loop ) {
        $params[] = 'loop=1';
    }

    $query = $params ? '?' . implode( '&', $params ) : '';

    /* =======================
     * YouTube (Short URL)
     * ======================= */
    if ( preg_match( '%youtu\.be/([^?]+)%', $url, $m ) ) {
        return [
            'type' => 'youtube',
            'url'  => 'https://www.youtube.com/embed/' . $m[1] . $query . '&playlist=' . $m[1],
        ];
    }

    /* =======================
     * YouTube (Long URL)
     * ======================= */
    if ( preg_match( '%youtube\.com/watch\?v=([^&]+)%', $url, $m ) ) {
        return [
            'type' => 'youtube',
            'url'  => 'https://www.youtube.com/embed/' . $m[1] . $query . '&playlist=' . $m[1],
        ];
    }

    /* =======================
     * Vimeo
     * ======================= */
    if ( preg_match( '%vimeo\.com/(\d+)%', $url, $m ) ) {
        return [
            'type' => 'vimeo',
            'url'  => 'https://player.vimeo.com/video/' . $m[1] . $query,
        ];
    }

    /* =======================
     * Self-hosted video
     * ======================= */
    if ( preg_match( '%\.(mp4|webm|ogg)(\?.*)?$%i', $url ) ) {
        return [
            'type' => 'mp4',
            'url'  => esc_url( $url ),
        ];
    }

    /* =======================
     * Unknown / Unsupported
     * ======================= */
    return [
        'type' => 'unknown',
        'url'  => esc_url( $url ),
    ];
}

