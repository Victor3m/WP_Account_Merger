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

  private $users;

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
    $this->users = array();

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
    add_action('admin_menu', array($this,'create_user_submenu'));
  }

  public function create_user_submenu() {
    add_submenu_page(
      'users.php',
      'WP Account Merger',
      'Merge Accounts',
      'manage_options',
      'wpam',
      array($this, 'render_user_submenu'),
    );
  }

  public function render_user_submenu() {
  ?>
    <div class="wrap">
      <h1>User Account Merger</h1>
      <form method="post">
        <table class="form-table">
          <tr>
            <th><label for="source_user_id">Source User ID:</label></th>
            <td><input list="source_users" type="text" id="source_user_id" name="source_user_id" autocomplete="off" required/></td>
            <datalist id="source_users"></datalist>
          </tr>
          <tr>
            <th><label for="target_user_id">Target User ID:</label></th>
            <td><input list="target_users" type="text" id="target_user_id" name="target_user_id" autocomplete="off" required/></td>
            <datalist id="target_users"></datalist>
          </tr>
        </table>
        <p class="submit">
          <input type="submit" name="get-data" class="button button-primary" value="Get User Data" />
        </p>
      </form>
      <?php
      if (isset($_POST['get-data'])) {
      ?>
      <h2>Account Details</h2>
      <form method="post">
      <?php
        $this->users[] = new Wpam_User_Data(get_user_by( 'login', $_POST['source_user_id']));
        $this->users[] = new Wpam_User_Data(get_user_by( 'login', $_POST['target_user_id']));
        foreach ($this->users as $user) {
          $validUser = $user->does_exist();
          if (!$validUser) {
            echo $validUser;
            return false;
          }
        }
        if (!$validUser) {
          echo 'Invalid User IDs';
          return false;
        }
        $user_selection = $this->display_account_details_choices($this->users);
        if (!$user_selection) {
          echo 'Invalid User IDs';
          return false;
        }
        is_plugin_active('woocommerce/woocommerce.php') ? $this->display_order_choices() : '';
        is_plugin_active('woocommerce-subscriptions/woocommerce-subscriptions.php') ? $this->display_subscription_choices() : '';
        is_plugin_active('woocommerce-memberships/woocommerce-memberships.php') ? $this->display_membership_choices() : '';
        ?>
        <p class="submit">
          <input type="submit" name="merge-data" class="button button-primary" value="Merge Accounts" />
        </p>
        <?php
        }  ?>
      </form>
    </div>
    <?php
  }

  public function display_account_details_choices() {
    $account1 = $this->users[0];
    $account2 = $this->users[sizeof($this->users) - 1];

    if (!$account1 || !$account2) {
      return false;
    }
    ?>
      <table id="account-details-table" class="wp-list-table widefat fixed">
        <thead>
          <tr>
            <th>UserName</th>
          <?php foreach ($this->users as $user) { ?> 
            <th id="<?php $user->get_account_detail('user_login') ?>">
            <input type="radio" name="select_all">
            <?php echo $user->get_account_detail('user_login') ?>
          </th> <?php } ?>
          </tr>
        </thead>
        <tbody>
        <?php foreach($account1->get_user_data() as $key => $val) { 
          if (is_array($val)) { 
            foreach ($val as $k => $v) { 
              $this->display_detail_section($key, $k); 
            }
          } else {
            $this->display_detail_section($key);
          }
        } ?>
        </tbody>
    </table>
    <br>
    <?php
    return true;
  }

  private function display_detail_section($key, $subkey = '') {
    if ($key === 'meta_data') { return false; }
    if ($this->users[0]->get_account_detail($key, $subkey) === "" && $this->users[sizeof($this->users) - 1]->get_account_detail($key, $subkey) === "") { return false; }

  ?>
    <tr>
      <td><?php echo $subkey === '' ? $key : $key . '_' . $subkey; ?></td>
      <?php 
        foreach ($this->users as $user) {
          $subkey === '' ? $this->display_account_detail($key, $user->get_account_detail($key)) : $this->display_account_detail($key . '_' . $subkey, $user->get_account_detail($key, $subkey)); 
        }
      ?>
    </tr>
  <?php
  }

  private function display_account_detail($key, $val) {
  ?>
    <td><input type="radio" name="<?php echo $key; ?>" value="<?php
      echo $val;
      ?>"><?php
      echo $val;
      ?></td>
  <?php 
  }

  public function display_order_choices() {
    // Retrieve the orders
    $source_orders = $this->users[0]->get_user_orders();
    $target_orders = $this->users[1]->get_user_orders();

    if (!$source_orders && !$target_orders) {
      return false;
    }

    // Display the orders
    $this->display_order_choices_table($source_orders, $target_orders);

    // Update the merge logic
    if (isset($_POST['merge_orders'])) {
      $selected_orders = $_POST['orders'];
    } else {
      $selected_orders = array();
    }

    return ($selected_orders) ? $selected_orders : false;
  }

  private function display_order_choices_table($source_orders, $target_orders) {
    ?>
    <table class="wp-list-table widefat fixed striped"> 
      <thead>
        <tr>
          <th><input type="checkbox" id="woo_orders" name="woo_orders"></th>
          <th>Customer ID</th>
          <th>Order ID</th>
          <th>Order Date</th>
          <th>Order Total</th>
          <th>Line Items</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if (is_array($source_orders)) {
          foreach ($source_orders as $order) {
            $this->display_order($order);
          }
        } else {
          $this->display_order($source_orders);
        }
        ?>
        <?php 
        if (is_array($target_orders)) {
          foreach ($target_orders as $order) {
            $this->display_order($order); 
          }
        } else {
          $this->display_order($target_orders);
        }
        ?>
      </tbody>
    </table>
    <br>
    <?php
  }

  private function display_order($order) {
    if (!$order) {
      return false;
    } else {
    $data = $order->get_data();
    ?>
    <tr>
      <td><input type="checkbox" name="orders" value="<?php echo $data['id']; ?>"></td>
        <td><?php echo $data['customer_id']; ?></td>
        <td><?php echo $data['id']; ?></td>
        <td><?php echo $data['date_created']->format('m-d-Y'); ?></td>
        <td><?php echo $data['total']; ?></td>
        <td><?php foreach($data['line_items'] as $line_item) { echo $line_item->get_name(); } ?></td>
    </tr>
    <?php }
  }

  public function display_subscription_choices() {
    // Retrieve the subscriptions
    $source_subscriptions = $this->users[0]->get_user_subscriptions();
    $target_subscriptions = $this->users[1]->get_user_subscriptions();

    if (!$source_subscriptions && !$target_subscriptions) {
      return false;
    }

    // Display the subscriptions
    ?>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th><input type="checkbox" id="woo_subscriptions" name="woo_subscriptions"></th>
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

  private function check_array_loop_and_display_subscriptions($array) {
    if (!$array) {
      return false;
    } elseif (is_array($array)) {
        foreach ($array as $subarray) { ?>
        <tr>
          <td><input type="checkbox" name="subscriptions" value="<?php echo $subarray->ID; ?>"></td>
          <td><?php echo $subarray->customer_id; ?></td>
          <td><?php echo $subarray->ID; ?></td>
          <td><?php echo $subarray->order_date; ?></td>
          <td><?php echo $subarray->order_total; ?></td>
        </tr>
    <?php } } else { ?>
        <tr>
          <td><input type="checkbox" name="subscriptions" value="<?php echo $array->ID; ?>"></td>
          <td><?php echo $array->customer_id; ?></td>
          <td><?php echo $array->ID; ?></td>
          <td><?php echo $array->order_date; ?></td>
          <td><?php echo $array->order_total; ?></td>
    </tr> 
    <?php }
  }

  public function display_membership_choices() {
    // Retrieve the memberships
    $source_memberships = $this->users[0]->get_user_memberships();
    $target_memberships = $this->users[1]->get_user_memberships();

    if (!$source_memberships && !$target_memberships) {
      return false;
    }

    // Display the memberships
    ?>
    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th><input type="checkbox" id="woo_memberships" name="woo_memberships"></th>
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

  private function check_array_loop_and_display_memberships($array) {
    if (!$array) {
      return false;
    } elseif (is_array($array)) {
        foreach ($array as $subarray) { ?>
        <tr>
          <td><input type="checkbox" name="memberships" value="<?php echo $subarray->id; ?>"></td>
          <td><?php echo $subarray->user_id; ?></td>
          <td><?php echo $subarray->id; ?></td>
          <td><?php echo get_post_meta($subarray->id, '_start_date', true); ?></td>
          <td><?php echo $subarray->status; ?></td>
        </tr>
    <?php } } else { ?>
        <tr>
          <td><input type="checkbox" name="memberships" value="<?php echo $array->id; ?>"></td>
          <td><?php echo $array->user_id; ?></td>
          <td><?php echo $array->id; ?></td>
          <td><?php echo get_post_meta($subarray->id, '_start_date', true); ?></td>
          <td><?php echo $array->status; ?></td>
    </tr> 
    <?php }
  }
}
