<?php

class Wpam_User_Data {

  protected $user = array();

  public function __construct($user) {
    $user_id = $user->ID;

    $this->user['id'] = $user_id;

    add_action('admin_menu',array( $this, 'check_user'));

    if ($this->user['id'] === 0) {
      return false;
    }
    
    $this->user['acct_details'] = $this->set_user_data($user_id);

    if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
      $this->user['orders'] = $this->set_user_orders($user_id);
    }

    if( is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php' ) ) {
      $this->user['subscriptions'] = $this->set_user_subscriptions($user_id);
    }

    if( is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) ) {
      $this->user['memberships'] = $this->set_user_memberships($user_id); 
    }
  }

  public function check_user() {
    if (!get_user($this->user['id'])) {
      $this->user['id'] = 0;
    }
  }

  public function set_user_data($user_id) {
    $data = get_userdata($user_id);
    if (!$data) {
      return false;
    } else {
      $data = $data->to_array();
    }

    if (is_plugin_active('woocommerce/woocommerce.php')) {
      $data = array_merge($data, $this->set_user_woo_data($user_id));
    }

    return $data;
  }

  public function set_user_woo_data($user_id) {
    $customer = new WC_Customer($user_id);
    if (!$customer) {
      return false;
    }

    return $customer->get_data();
  }

  public function set_user_orders($user_id) {
    $orders = wc_get_orders(array(
      'customer_id' => $user_id,
      'limit' => -1
    ));
    if (!$orders) {
      return false;
    }

    return $orders;
  }

  public function set_user_subscriptions($user_id) {
    $subscriptions = wcs_get_subscriptions(array(
      'customer_id' => $user_id,
      'limit' => -1
    ));
    if (!$subscriptions) {
      return false;
    }

    return $subscriptions;
  }

  public function set_user_memberships($user_id) {
    $memberships = wc_memberships_get_user_memberships($user_id);
    if (!$memberships) {
      return false;
    }

    return $memberships;
  }

  public function does_exist() {
    return $this->user['id'] !== 0;
  }

  public function get_user_data() {
    return $this->user['acct_details'];
  }

  public function get_account_detail($key, $subkey = '') {
    return $subkey === '' ? $this->user['acct_details'][$key] : $this->user['acct_details'][$key][$subkey];
  }

  public function get_user_orders() {
    return $this->user['orders'];
  }

  public function get_user_subscriptions() {
    return $this->user['subscriptions'];
  }

  public function get_user_memberships() {
    return $this->user['memberships'];
  }
}
