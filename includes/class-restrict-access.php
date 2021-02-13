<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.tammersoft.com
 * @since      1.0.0
 *
 * @package    Restrict_Access
 * @subpackage Restrict_Access/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Restrict_Access
 * @subpackage Restrict_Access/includes
 * @author     Anssi Laitila <anssi.laitila@tammersoft.com>
 */
class Restrict_Access {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Restrict_Access_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {

    if (defined('RESTRICT_ACCESS_VERSION')) {
      $this->version = RESTRICT_ACCESS_VERSION;
    }

    $this->plugin_name = 'restrict-access';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();

  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Restrict_Access_Loader. Orchestrates the hooks of the plugin.
   * - Restrict_Access_i18n. Defines internationalization functionality.
   * - Restrict_Access_Admin. Defines all hooks for the admin area.
   * - Restrict_Access_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ra-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ra-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ra-admin.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ra-admin-query.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ra-admin-ajax.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ra-admin-walker.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ra-admin-main.php';
    require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-ra-admin-maintenance.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-ra-public.php';

    /**
     * The class responsible for defining custom fields for the custom post type
     */
    require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-ra-helpers.php';

    $this->loader = new Restrict_Access_Loader();

  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Restrict_Access_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {

    $plugin_i18n = new Restrict_Access_i18n();

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {

    $plugin_admin = new Restrict_Access_Admin($this->get_plugin_name(), $this->get_version());
    $plugin_admin_maintenance = new RestrictAccessAdminMaintenance();
    $plugin_admin_query = new RestrictAccessAdminQuery();
    $plugin_admin_ajax = new RestrictAccessAdminAjax();
    $plugin_admin_main = new RestrictAccessAdminMain();

    $this->loader->add_action('plugins_loaded', $plugin_admin_main, 'update_db_check');

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

    // Ajax
    $this->loader->add_action('wp_ajax_nopriv_ra_restrict_access', $plugin_admin_ajax, 'ra_restrict_access');
    $this->loader->add_action('wp_ajax_ra_restrict_access', $plugin_admin_ajax, 'ra_restrict_access');

    // Maintenance
    $this->loader->add_action('plugins_loaded', $plugin_admin_maintenance, 'actions');

    // Menu page
    $this->loader->add_action('admin_menu', $plugin_admin_main, 'add_main_menu_item');

    // Query
    $this->loader->add_filter('request', $plugin_admin_query, 'restrict_access_request');

  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_public_hooks() {

    $plugin_public = new Restrict_Access_Public($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_enqueue_scripts', $plugin_public, 'enqueue_styles');

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Restrict_Access_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

}
