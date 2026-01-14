<?php
if (!defined('ABSPATH')) exit;

class CODENIT_WC_Video_Settings {

    public function __construct() {
        // Add admin menu
        add_action('admin_menu', [$this, 'add_settings_page']);

        // Register settings
        add_action('admin_init', [$this, 'register_settings']);
        
        add_action('admin_notices', [$this, 'settings_updated_notice']);
    }

    public function settings_updated_notice() {

        // phpcs:disable WordPress.Security.NonceVerification.Recommended
        $page = isset($_GET['page']) ? sanitize_text_field( wp_unslash($_GET['page']) ) : '';
    
        $updated = isset($_GET['settings-updated']) ? sanitize_text_field( wp_unslash($_GET['settings-updated']) ) : '';
        // phpcs:enable WordPress.Security.NonceVerification.Recommended
    
        if ( $page === 'codenit-wc-video-settings' && $updated === 'true' ) {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Video settings saved successfully.', 'codenitive-product-image-video' ); ?></p>
            </div>
            <?php
        }
    }


    /**
     * Add settings submenu under WooCommerce
     */
    public function add_settings_page() {
        add_submenu_page(
            'woocommerce',
            'Product Image Video Settings by Codenitive', // Page title
            'Product Image Video',          // Menu title
            'manage_options',               // Capability
            'codenit-wc-video-settings',    // Menu slug
            [$this, 'settings_page_html']   // Callback
        );
    }

    /**
     * Register settings and fields
     */
    public function register_settings() {
        // Register options
        register_setting(
            'codenit_wc_video_settings_group', 
            'codenit_wc_video_enabled',
            [
                'sanitize_callback' => [$this, 'sanitize_checkbox']
            ]
        );
        
        register_setting(
            'codenit_wc_video_settings_group', 
            'codenit_wc_video_autoplay',
            [
                'sanitize_callback' => [$this, 'sanitize_checkbox']
            ]
        );
        
        register_setting(
            'codenit_wc_video_settings_group', 
            'codenit_wc_video_loop',
            [
                'sanitize_callback' => [$this, 'sanitize_checkbox']
            ]
        );
        
        register_setting(
            'codenit_wc_video_settings_group', 
            'codenit_wc_video_overlay',
            [
                'sanitize_callback' => 'esc_url_raw'
            ]
        );
        
        register_setting(
            'codenit_wc_video_settings_group', 
            'codenit_wc_video_device',
            [
                'sanitize_callback' => [$this, 'sanitize_select']
            ]
        );


        // Settings section
        add_settings_section(
            'codenit_wc_video_section',
            'Codenitive Video Display Settings',
            null,
            'codenit-wc-video-settings'
        );

        // Fields
        add_settings_field('codenit_wc_video_enabled', 'Enable Video', [$this, 'field_enabled_html'], 'codenit-wc-video-settings', 'codenit_wc_video_section');
        add_settings_field('codenit_wc_video_autoplay', 'Autoplay Video', [$this, 'field_autoplay_html'], 'codenit-wc-video-settings', 'codenit_wc_video_section');
        add_settings_field('codenit_wc_video_loop', 'Loop Video', [$this, 'field_loop_html'], 'codenit-wc-video-settings', 'codenit_wc_video_section');
        add_settings_field('codenit_wc_video_overlay', 'Custom Overlay Icon', [$this, 'field_overlay_html'], 'codenit-wc-video-settings', 'codenit_wc_video_section');
        add_settings_field('codenit_wc_video_device', 'Enable Video On', [$this, 'field_device_html'], 'codenit-wc-video-settings', 'codenit_wc_video_section');
        
        $this->plugin_action_links_init();
        
    }
    
    public function sanitize_checkbox($value) {
        return $value === 'yes' ? 'yes' : 'no';
    }
    
    public function sanitize_select($value) {
        $allowed = ['all', 'desktop', 'mobile'];
        return in_array($value, $allowed) ? $value : 'all';
    }

    // ======== Fields HTML ========

    public function field_enabled_html() {
        $value = get_option('codenit_wc_video_enabled', 'yes');
        echo '<input type="checkbox" name="codenit_wc_video_enabled" value="yes"' . checked('yes', $value, false) . '> Enable video in product gallery';
    }

    public function field_autoplay_html() {
        $value = get_option('codenit_wc_video_autoplay', 'no');
        echo '<input type="checkbox" name="codenit_wc_video_autoplay" value="yes"' . checked('yes', $value, false) . '> Autoplay video when visible';
    }

    public function field_loop_html() {
        $value = get_option('codenit_wc_video_loop', 'no');
        echo '<input type="checkbox" name="codenit_wc_video_loop" value="yes"' . checked('yes', $value, false) . '> Loop video';
    }

    public function field_overlay_html() {
        $value = get_option('codenit_wc_video_overlay', '');
        ?>
        <input type="text" name="codenit_wc_video_overlay" id="codenit_wc_video_overlay" value="<?php echo esc_url($value); ?>" style="width:60%;" />
        <button class="button upload_overlay_button">Upload Icon</button>
        <p class="description">Optional: Upload a custom play icon overlay for the video thumbnail.</p>
        <script>
        jQuery(document).ready(function($){
            var mediaUploader;
            $('.upload_overlay_button').click(function(e){
                e.preventDefault();
                if (mediaUploader) { mediaUploader.open(); return; }
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: 'Select Overlay Icon',
                    button: { text: 'Use this icon' },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#codenit_wc_video_overlay').val(attachment.url);
                });
                mediaUploader.open();
            });
        });
        </script>
        <?php
    }

    public function field_device_html() {
        $value = get_option('codenit_wc_video_device', 'all');
        echo '<select name="codenit_wc_video_device">
                <option value="all"' . selected($value, 'all', false) . '>All Devices</option>
                <option value="desktop"' . selected($value, 'desktop', false) . '>Desktop Only</option>
                <option value="mobile"' . selected($value, 'mobile', false) . '>Mobile Only</option>
              </select>';
    }

    // ======== Settings Page HTML ========
    public function settings_page_html() {
        ?>
        <div class="wrap">
            <h1>WooCommerce Product Image Video Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('codenit_wc_video_settings_group');
                do_settings_sections('codenit-wc-video-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function plugin_action_links($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=codenit-wc-video-settings') . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    public function plugin_action_links_init() {
        add_filter('plugin_action_links_' . CODENITIVE_WCPV_BASENAME, [$this, 'plugin_action_links']);
    }

}
