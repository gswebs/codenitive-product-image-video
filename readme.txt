=== Codenitive Product Image Video ===
Contributors: codenitive
Tags: woocommerce, product video, youtube, vimeo, self hosted video
Requires at least: 5.6
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Adds video support to WooCommerce product images. Display YouTube, Vimeo, or self-hosted MP4 videos directly in the product gallery.

== Description ==

WC Product Image Video allows you to add video URLs to WooCommerce product images and display them in the product gallery with optional overlay icons, autoplay, and loop settings.

**Features:**
* Add YouTube, Vimeo, or MP4 video URLs to each product image.
* Show videos as slides in the product gallery.
* Optional custom overlay play icon.
* Autoplay and loop options configurable in settings.
* Works with WooCommerce Classic and Blocks galleries.
* Supports mobile/desktop visibility settings.

This plugin enhances your WooCommerce product galleries by letting your customers view video content directly in the product image slider.

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **WooCommerce > Product Image Video** to configure autoplay, loop, overlay icon, and device settings.
4. Edit a product, open the Media Library, and add a video URL to any image using the **Video URL** field.

== Frequently Asked Questions ==

= Which video formats are supported? =
You can use YouTube, Vimeo, or MP4 (self-hosted) videos.

= How do I add a video to a product? =
Edit the product in WooCommerce, open the Media Library for the product images, and enter the video URL in the **Video URL** field.

= Can I autoplay videos? =
Yes, enable **Autoplay video when visible** in the plugin settings.

= Can I use a custom play overlay icon? =
Yes, upload an overlay icon in the plugin settings.

= Will this work on mobile? =
Yes, you can choose to enable videos for desktop only, mobile only, or all devices.

== Screenshots ==

1. Product image with video overlay icon.
2. Video playing inside WooCommerce gallery.
3. Plugin settings page with autoplay, loop, overlay, and device options.

== Changelog ==

= 1.0.3 =
* Improved WordPress.org compliance and settings sanitization.

= 1.0.2 =
* Added the Preview Video in images

= 1.0.1 =
* Fixed the play icon issue(in the helpers.php file `wc_single_product_image_thumbnail_html` filter used to add play icon)

= 1.0.0 =
* Initial release
* Add video URL field to product images
* Display video in product gallery
* Settings page for autoplay, loop, overlay, and device options
* Supports YouTube, Vimeo, and MP4 videos

== Upgrade Notice ==

= 1.0.3 =
* Improved WordPress.org compliance and settings sanitization.

= 1.0.2 =
* Added the Preview Video

= 1.0.1 =
* Fixed the play icon issue

= 1.0.0 =
Initial release. No previous versions.
