<?php

namespace SLCA\WooCommerce;

use wpCloud\StatelessMedia\Compatibility;
use wpCloud\StatelessMedia\Helper;

/**
 * Class WooCommerce
 */

class WooCommerce extends Compatibility {
  protected $id = 'woocommerce';
  protected $title = 'WooCommerce';
  protected $constant = 'WP_STATELESS_COMPATIBILITY_WOOCOMMERCE';
  protected $description = 'Ensures compatibility with WooCommerce.';
  protected $plugin_file = [ 'woocommerce/woocommerce.php' ];

  /**
   * @param $sm
   */
  public function module_init( $sm ) {
    add_filter( 'stateless_skip_cache_busting', array( $this, 'skip_cache_busting' ), 10, 2 );
    add_filter( 'upload_dir', array($this, 'filter_upload_dir'), 100);
  }

  /**
   * skip cache busting for template file name.a
   * @param $return
   * @param $filename
   * @return mixed
   */
  public function skip_cache_busting( $return, $filename ) {
    if ( doing_action('wp_ajax_woocommerce_do_ajax_product_export') ) {
      return $filename;
    }

    // Detect export flow for older versions of WooCommerce
    // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
    $backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 8 );

    $check = $backtrace && isset( $backtrace[7] ) && isset( $backtrace[7]['class'] ) ? $backtrace[7]['class'] : '';

    if( strpos( $check, 'WC_CSV_Exporter' ) !== false ) {
      return $filename;
    }
    return $return;
  }

  /**
   * In StateLess mode, we allow to save temporary export files in the local storage under /tmp.
   * @param $uploads
   * @return array
   */
  public function filter_upload_dir($uploads) {
    if ( !ud_get_stateless_media()->is_mode('stateless') ) {
      return $uploads;
    }

    // Save temporary files to /tmp during export
    if ( doing_action('wp_ajax_woocommerce_do_ajax_product_export') ) {
      $uploads['basedir'] = '/tmp';
    }

    // Save temporary files to /tmp during download
    // Nonce verification is handled by WooCommerce
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( isset( $_GET['action'] ) && 'download_product_csv' === sanitize_text_field( wp_unslash($_GET['action']) ) ) {
      $uploads['basedir'] = '/tmp';
    }

    return $uploads;
  }
}
