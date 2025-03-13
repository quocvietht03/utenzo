<?php
/**
 * Import pack data package demo
 *
 */
$plugin_includes = array(
  array(
    'name'     => __( 'Elementor Website Builder', 'utenzo' ),
    'slug'     => 'elementor',
  ),
  array(
    'name'     => __( 'Elementor Pro', 'utenzo' ),
    'slug'     => 'elementor-pro',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'elementor-pro.zip',
  ),
  array(
    'name'     => __( 'Smart Slider 3 Pro', 'utenzo' ),
    'slug'     => 'nextend-smart-slider3-pro',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'nextend-smart-slider3-pro.zip',
  ),
  array(
    'name'     => __( 'Advanced Custom Fields PRO', 'utenzo' ),
    'slug'     => 'advanced-custom-fields-pro',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'advanced-custom-fields-pro.zip',
  ),
  array(
    'name'     => __( 'Gravity Forms', 'utenzo' ),
    'slug'     => 'gravityforms',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'gravityforms.zip',
  ),
  array(
    'name'     => __( 'Newsletter', 'utenzo' ),
    'slug'     => 'newsletter',
  ),
  array(
    'name'     => __( 'WooCommerce', 'utenzo' ),
    'slug'     => 'woocommerce',
  ),
  array(
    'name'     => __( 'WooCommerce Booking & Rental System', 'utenzo' ),
    'slug'     => 'woocommerce-rental-and-booking',
    'source'   => IMPORT_REMOTE_SERVER_PLUGIN_DOWNLOAD . 'woocommerce-rental-and-booking.zip',
  ),

);

return apply_filters( 'utenzo/import_pack/package_demo', [
    [
        'package_name' => 'utenzo-main',
        'preview' => get_template_directory_uri() . '/screenshot.jpg',
        'url_demo' => 'https://utenzo.beplusthemes.com/',
        'title' => __( 'Utenzo Demo', 'utenzo' ),
        'description' => __( 'Utenzo main demo.', 'utenzo' ),
        'plugins' => $plugin_includes,
    ],
] );
