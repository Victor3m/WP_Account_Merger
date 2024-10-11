<?php

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpam-user-data.php';

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/Victor3m
 * @since      1.0.0
 *
 * @package    Wpam
 * @subpackage Wpam/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpam
 * @subpackage Wpam/admin
 * @author     Victor3m <jhester911@gmail.com>
 */
class Wpam_Admin {

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
    $this->register_admin_hooks();
    $this->user_data = new Wpam_User_Data();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpam_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpam_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpam-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpam_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpam_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpam-admin.js', array( 'jquery' ), $this->version, false );

	}

  public function register_admin_hooks() {
    add_action('admin_menu', array($this,'create_admin_page'));
  }

  public function create_admin_page() {
    add_menu_page(
      'WP Account Merger',
      'WP Account Merger',
      'manage_options',
      'wpam',
      array($this, 'render_admin_page'),
    );
  }

  public function render_admin_page() {
  ?>
    <div class="wrap">
      <h1>User Account Merger</h1>
      <form method="post">
        <table class="form-table">
          <tr>
            <th><label for="source_user_id">Source User ID:</label></th>
            <td><input type="text" id="source_user_id" name="source_user_id" /></td>
          </tr>
          <tr>
            <th><label for="target_user_id">Target User ID:</label></th>
            <td><input type="text" id="target_user_id" name="target_user_id" /></td>
          </tr>
        </table>
        <p class="submit">
          <input type="submit" name="submit" class="button button-primary" value="Map User Data" />
        </p>
      </form>
      <?php
      if (isset($_POST['submit'])) {
        $source_user_id = $_POST['source_user_id'];
        $target_user_id = $_POST['target_user_id'];

        $mapped_data = $this->user_data->map_user_data($source_user_id, $target_user_id);

        echo '<h2>Mapped User Data:</h2>';
        echo '<pre>';
        print_r($mapped_data);
        echo '</pre>';
      } ?>
    </div>
  <?php
  }
}
