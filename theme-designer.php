<?php
/**
 * Plugin Name: Theme Designer
 * Plugin URI:  http://themehybrid.com/plugins/theme-designer
 * Description: Awesomesauce.
 * Version:     1.0.0-dev
 * Author:      Justin Tadlock
 * Author URI:  http://themehybrid.com
 * Text Domain: theme-designer
 * Domain Path: /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   ThemeDesigner
 * @version   1.0.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2015, Justin Tadlock
 * @link      http://themehybrid.com/plugins/theme-designer
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Singleton class that sets up and initializes the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
final class THDS_Plugin {

	/**
	 * Directory path to the plugin folder.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir_path = '';

	/**
	 * Directory URI to the plugin folder.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir_uri = '';

	/**
	 * JavaScript directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $js_uri = '';

	/**
	 * CSS directory URI.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $css_uri = '';

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Magic method to output a string if trying to use the object as a string.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __toString() {
		return 'theme-designer';
	}

	/**
	 * Magic method to keep the object from being cloned.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Whoah, partner!', 'theme-designer' ), '1.0.0' );
	}

	/**
	 * Magic method to keep the object from being unserialized.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Whoah, partner!', 'theme-designer' ), '1.0.0' );
	}

	/**
	 * Magic method to prevent a fatal error when calling a method that doesn't exist.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __call( $method = '', $args = array() ) {
		_doing_it_wrong( "THDS_Plugin::{$method}", __( 'Method does not exist.', 'theme-designer' ), '1.0.0' );
		unset( $method, $args );
		return null;
	}

	/**
	 * Initial plugin setup.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		$this->dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->dir_uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );

		$this->js_uri  = trailingslashit( $this->dir_uri . 'js'  );
		$this->css_uri = trailingslashit( $this->dir_uri . 'css' );
	}

	/**
	 * Loads include and admin files for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function includes() {

		// Load core files.
		require_once( $this->dir_path . 'inc/core/filters.php'    );
		require_once( $this->dir_path . 'inc/core/options.php'    );
		require_once( $this->dir_path . 'inc/core/meta.php'       );
		require_once( $this->dir_path . 'inc/core/rewrite.php'    );
		require_once( $this->dir_path . 'inc/core/post-types.php' );
		require_once( $this->dir_path . 'inc/core/sticky.php'     );
		require_once( $this->dir_path . 'inc/core/taxonomies.php' );

		// Load theme files.
		require_once( $this->dir_path . 'inc/template/author.php'  );
		require_once( $this->dir_path . 'inc/template/feature.php' );
		require_once( $this->dir_path . 'inc/template/general.php' );
		require_once( $this->dir_path . 'inc/template/subject.php' );
		require_once( $this->dir_path . 'inc/template/theme.php'   );

		// Load WordPress.org integration files.
		require_once( $this->dir_path . 'inc/wporg/class-wporg-theme.php'         );
		require_once( $this->dir_path . 'inc/wporg/class-wporg-theme-factory.php' );
		require_once( $this->dir_path . 'inc/wporg/functions-wporg.php'           );
		require_once( $this->dir_path . 'inc/wporg/template-wporg.php'            );

		// Load admin files.
		if ( is_admin() ) {
			require_once( $this->dir_path . 'admin/functions-admin.php'     );
			require_once( $this->dir_path . 'admin/class-manage-themes.php' );
			require_once( $this->dir_path . 'admin/class-theme-edit.php'    );
			require_once( $this->dir_path . 'admin/class-settings.php'      );
		}
	}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Internationalize the text strings used.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		// Register activation hook.
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
	}

	/**
	 * Loads the translation files.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		load_plugin_textdomain( 'theme-designer', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
	}

	/**
	 * Method that runs only when the plugin is activated.
	 *
	 * @since  1.0.0
	 * @access public
	 * @global $wpdb
	 * @return void
	 */
	public function activation() {

		// Get the administrator role.
		$role = get_role( 'administrator' );

		// If the administrator role exists, add required capabilities for the plugin.
		if ( ! is_null( $role ) ) {

			// Taxonomy caps.
			$role->add_cap( 'manage_theme_subjects' );
			$role->add_cap( 'manage_theme_features' );

			// Post type caps.
			$role->add_cap( 'create_theme_projects'           );
			$role->add_cap( 'edit_theme_projects'             );
			$role->add_cap( 'edit_others_theme_projects'      );
			$role->add_cap( 'publish_theme_projects'          );
			$role->add_cap( 'read_private_theme_projects'     );
			$role->add_cap( 'delete_theme_projects'           );
			$role->add_cap( 'delete_private_theme_projects'   );
			$role->add_cap( 'delete_published_theme_projects' );
			$role->add_cap( 'delete_others_theme_projects'    );
			$role->add_cap( 'edit_private_theme_projects'     );
			$role->add_cap( 'edit_published_theme_projects'   );
		}
	}
}

/**
 * Gets the instance of the `THDS_Plugin` class.  This function is useful for quickly grabbing data
 * used throughout the plugin.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function thds_plugin() {
	return THDS_Plugin::get_instance();
}

// Let's do this thang!
thds_plugin();
