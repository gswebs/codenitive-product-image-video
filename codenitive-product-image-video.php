<?php
/*
* Plugin Name: Codenitive Product Image Video
* Plugin URI:  https://github.com/gswebs/codenitive-product-image-video
* Description: Adds a custom field to WooCommerce product images to allow video URLs and display them in the product gallery.
* Version: 1.0.3
* Requires at least: 5.6
* Tested up to: 6.9
* Requires PHP: 7.4
* Author: Codenitive
* Author URI: https://codenitive.com
* Requires Plugins: woocommerce
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: codenitive-product-image-video
*
* @package codenitive-product-image-video
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Codenitive_WC_Product_Video {

    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'plugins_loaded', [ $this, 'init' ] );
    }

    public function init() {
        if ( ! class_exists( 'WooCommerce' ) ) return;

        $this->define_constants();
        $this->includes();

        new CODENIT_WC_Video_Settings();
        new CODENIT_WC_Product_Image_Video_CF();
        new CODENIT_WC_Product_Image_Video_Frontend();
    }

    private function define_constants() {
        define( 'CODENITIVE_WCPV_BASENAME', plugin_basename(__FILE__) );
        define( 'CODENITIVE_WCPV_PATH', plugin_dir_path( __FILE__ ) );
        define( 'CODENITIVE_WCPV_URL', plugin_dir_url( __FILE__ ) );
        define( 'CODENITIVE_WCPV_VERSION', '1.1.0' );
    }

    private function includes() {
        require_once CODENITIVE_WCPV_PATH . 'includes/helpers.php';
        require_once CODENITIVE_WCPV_PATH . 'includes/admin/settings.php';
        require_once CODENITIVE_WCPV_PATH . 'includes/admin/cf-images.php';
        require_once CODENITIVE_WCPV_PATH . 'includes/class-frontend.php';
    }
}

Codenitive_WC_Product_Video::instance();