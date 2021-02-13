<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.tammersoft.com
 * @since      1.0.0
 *
 * @package    Restrict_Access
 * @subpackage Restrict_Access/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Restrict_Access
 * @subpackage Restrict_Access/admin
 * @author     Anssi Laitila <anssi.laitila@tammersoft.com>
 */
class Restrict_Access_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  public function enqueue_styles() {
    wp_enqueue_style($this->plugin_name, RESTRICT_ACCESS_URI . 'dist/css/a.css', array(), $this->version, 'all');
  }

  public function enqueue_scripts() {
    wp_enqueue_script($this->plugin_name, RESTRICT_ACCESS_URI . 'dist/js/a.js', array('jquery'), $this->version, false);
  }

}
