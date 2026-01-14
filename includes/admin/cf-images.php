<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class CODENIT_WC_Product_Image_Video_CF {

    public function __construct() {
        add_filter('attachment_fields_to_edit', [$this, 'add_video_field_to_attachment'], 10, 2);
        add_filter('attachment_fields_to_save', [$this, 'save_video_field'], 10, 2);
    }

    public function add_video_field_to_attachment($form_fields, $post) {
        $video_url = get_post_meta($post->ID, '_codenit_wc_video_url', true);
        $form_fields['codenit_wc_video_url'] = [
            'label' => __( 'Video URL', 'product-image-video-gallery-for-woocommerce' ),
            'input' => 'text',
            'value' => $video_url,
            'helps' => 'Add a video URL (YouTube, Vimeo, MP4) for this product image.'
        ];

        if ( $video_url ) {

            // Check if it's an oEmbed-supported URL
            $preview = wp_oembed_get( esc_url( $video_url ) );
        
            if ( $preview ) {
                // oEmbed preview (YouTube, Vimeo, etc.)
                $form_fields['codenit_video_preview'] = [
                    'label' => __( 'Video Preview', 'product-image-video-gallery-for-woocommerce' ),
                    'input' => 'html',
                    'html'  => $preview,
                ];
            } else {
                // Fallback for self-hosted video (MP4)
                $file_ext = pathinfo( $video_url, PATHINFO_EXTENSION );
                if ( strtolower($file_ext) === 'mp4' ) {
                    $form_fields['codenit_video_preview'] = [
                        'label' => __( 'Video Preview', 'product-image-video-gallery-for-woocommerce' ),
                        'input' => 'html',
                        'html'  => '<video width="320" height="180" controls>
                                      <source src="' . esc_url($video_url) . '" type="video/mp4">
                                      Your browser does not support the video tag.
                                    </video>',
                    ];
                }
            }
        }

        return $form_fields;
    }

    public function save_video_field($post, $attachment) {
        $video_url = isset($attachment['codenit_wc_video_url']) ? trim($attachment['codenit_wc_video_url']) : '';
        if ( ! empty( $video_url ) ) {
            update_post_meta($post['ID'], '_codenit_wc_video_url', esc_url_raw($video_url));
        } else {
            delete_post_meta($post['ID'], '_codenit_wc_video_url');
        }
        return $post;
    }
}
