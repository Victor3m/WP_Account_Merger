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
    add_submenu_page(
      'users.php',
      'WP Account Merger',
      'Merge Accounts',
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
        $source_id = intval($_POST['source_user_id']);
        $target_id = intval($_POST['target_user_id']);
        $user_selection = $this->display_account_details($source_id, $target_id);
        if (!$user_selection) {
          echo 'Invalid User IDs';
          return false;
        }
        is_plugin_active('woocommerce/woocommerce.php') ? $this->display_order_choices($source_id, $target_id) : '';
        is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php') ? $this->display_subscription_choices($source_id, $target_id) : '';
        is_plugin_active('woocommerce-memberships/woocommerce-memberships.php') ? $this->display_membership_choices($source_id, $target_id) : '';
      } ?>
    </div>
    <?php
  }

  public function display_account_details($source_id, $target_id) {
    $account1 = get_userdata($source_id);
    $account2 = get_userdata($target_id);
    if (!$account1 || !$account2) {
      return false;
    }
    ?>
      <table class="wp-list-table widefat fixed striped">
        <thead>
          <tr>
            <th>Account Details</th>
            <th>Source</th>
            <th>Target</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>ID</td>
            <td><input type="radio" name="id" value="<?php echo $account1->ID; ?>"><?php echo $account1->ID; ?></td>
            <td><input type="radio" name="id" value="<?php echo $account2->ID; ?>" <?php checked(true); ?> ><?php echo $account2->ID; ?></td>
          </tr>
          <tr>
            <td>Name</td>
            <td><input type="radio" name="name" value="<?php echo $account1->user_nicename; ?>"><?php echo $account1->user_nicename; ?></td>
          <td><input type="radio" name="name" value="<?php echo $account2->user_nicename; ?>" <?php checked(true); ?> ><?php echo $account2->user_nicename; ?></td>
          </tr>
          <tr>
            <td>Email</td>
            <td><input type="radio" name="email" value="<?php echo $account1->user_email; ?>"><?php echo $account1->user_email; ?></td>
            <td><input type="radio" name="email" value="<?php echo $account2->user_email; ?>" <?php checked(true); ?> ><?php echo $account2->user_email; ?></td>
          </tr>
          <!-- Add more fields as needed -->
        </tbody>
    </table>
    <br>
    <?php
    return true;
  }

  public function display_membership_choices($source_account_id, $target_account_id) {
    // Retrieve the memberships
    $source_memberships = $this->user_data->get_user_memberships($source_account_id);
    $target_memberships = $this->user_data->get_user_memberships($target_account_id);

    // Display the memberships
    ?>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>Select</th>
          <th>Customer ID</th>
          <th>Membership ID</th>
          <th>Membership Date</th>
          <th>Membership Status</th>
        </tr>
      </thead>
      <tbody>
        <?php $this->check_array_loop_and_display_memberships($source_memberships); ?>
        <?php $this->check_array_loop_and_display_memberships($target_memberships); ?>
      </tbody>
    </table>
    <br>
    <?php
  }

  public function display_subscription_choices($source_account_id, $target_account_id) {
    // Retrieve the subscriptions
    $source_subscriptions = $this->user_data->get_user_subscriptions($source_account_id);
    $target_subscriptions = $this->user_data->get_user_subscriptions($target_account_id);

    // Display the subscriptions
    ?>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>Select</th>
          <th>Customer ID</th>
          <th>Subscription ID</th>
          <th>Subscription Date</th>
          <th>Subscription Status</th>
        </tr>
      </thead>
      <tbody>
        <?php $this->check_array_loop_and_display_subscriptions($source_subscriptions); ?>
        <?php $this->check_array_loop_and_display_subscriptions($target_subscriptions); ?>
      </tbody>
    </table>
    <br>
    <?php

    // Update the merge logic
    if (isset($_POST['merge_subscriptions'])) {
      $selected_subscriptions = $_POST['subscriptions'];
    } else {
      $selected_subscriptions = array();
    }

    return $selected_subscriptions ? $selected_subscriptions : false;
  }

  public function display_order_choices($source_account_id, $target_account_id) {
    // Retrieve the orders
    $source_orders = $this->user_data->get_user_orders($source_account_id);
    $target_orders = $this->user_data->get_user_orders($target_account_id);

    // Display the orders
    ?>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th>Select</th>
          <th>Customer ID</th>
          <th>Order ID</th>
          <th>Order Date</th>
          <th>Order Total</th>
        </tr>
      </thead>
      <tbody>
        <?php $this->check_array_loop_and_display_orders($source_orders); ?>
        <?php $this->check_array_loop_and_display_orders($target_orders); ?>
      </tbody>
    </table>
    <br>
    <?php

    // Update the merge logic
    if (isset($_POST['merge_orders'])) {
      $selected_orders = $_POST['orders'];
    } else {
      $selected_orders = array();
    }

    return ($selected_orders) ? $selected_orders : false;
  }

  private function check_array_loop_and_display_orders($array) {
    if (!$array) {
      return false;
    } elseif (is_array($array)) {
        foreach ($array as $subarray) { ?>
        <tr>
      <td><input type="checkbox" name="orders" value="<?php echo $subarray->ID; ?> " <?php checked(true); ?> ></td>
          <td><?php echo $subarray->get_customer_id(); ?></td>
          <td><?php echo $subarray->ID; ?></td>
          <td><?php echo $subarray->order_date; ?></td>
          <td><?php echo $subarray->order_total; ?></td>
        </tr>
    <?php } } else { ?>
        <tr>
          <td><input type="checkbox" name="orders" value="<?php echo $array->ID; ?>" <?php checked(true); ?> ></td>
          <td><?php echo $subarray->get_customer_id(); ?></td>
          <td><?php echo $array->ID; ?></td>
          <td><?php echo $array->order_date; ?></td>
          <td><?php echo $array->order_total; ?></td>
    </tr> 
    <?php }
  }

  private function check_array_loop_and_display_subscriptions($array) {
    if (!$array) {
      return false;
    } elseif (is_array($array)) {
        foreach ($array as $subarray) { ?>
        <tr>
          <td><input type="checkbox" name="orders" value="<?php echo $subarray->ID; ?>" <?php checked(true); ?> ></td>
          <td><?php echo $subarray->customer_id; ?></td>
          <td><?php echo $subarray->ID; ?></td>
          <td><?php echo $subarray->order_date; ?></td>
          <td><?php echo $subarray->order_total; ?></td>
        </tr>
    <?php } } else { ?>
        <tr>
          <td><input type="checkbox" name="orders" value="<?php echo $array->ID; ?>" <?php checked(true); ?> ></td>
          <td><?php echo $array->customer_id; ?></td>
          <td><?php echo $array->ID; ?></td>
          <td><?php echo $array->order_date; ?></td>
          <td><?php echo $array->order_total; ?></td>
    </tr> 
    <?php }
  }

  private function check_array_loop_and_display_memberships($array) {
    if (!$array) {
      return false;
    } elseif (is_array($array)) {
        foreach ($array as $subarray) { ?>
        <tr>
          <td><input type="checkbox" name="orders" value="<?php echo $subarray->id; ?>" <?php checked(true); ?> ></td>
          <td><?php echo $subarray->user_id; ?></td>
          <td><?php echo $subarray->id; ?></td>
          <td><?php echo get_post_meta($subarray->id, '_start_date', true); ?></td>
          <td><?php echo $subarray->status; ?></td>
        </tr>
    <?php } } else { ?>
        <tr>
          <td><input type="checkbox" name="orders" value="<?php echo $array->id; ?>" <?php checked(true); ?> ></td>
          <td><?php echo $array->user_id; ?></td>
          <td><?php echo $array->id; ?></td>
          <td><?php echo get_post_meta($subarray->id, '_start_date', true); ?></td>
          <td><?php echo $array->status; ?></td>
    </tr> 
    <?php }
  }
}
