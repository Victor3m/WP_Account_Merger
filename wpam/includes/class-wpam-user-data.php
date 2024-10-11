<?php

class Wpam_User_Data {
  public function wp_am_get_user_data($user_id) {
    $user_data = array();
    $user = get_userdata($user_id);

    if (!$user) {
      return false;
    }

    $user_data['username'] = $user->user_login;
    $user_data['email'] = $user->user_email;
    $user_data['roles'] = $user->rolse;


    return $user_data;
  }

  public function get_user_order_ids($user_id) {
    $orders = wc_get_orders(array('customer_id' => $user_id));
    if (!$orders) {
      return false;
    }

    $order_ids = array();

    foreach ($orders as $order) {
      $order_ids[] = $order->get_id();
    }

    return $order_ids;
  }

  public function get_user_subscription_ids($user_id) {
    $subscriptions = wcs_get_subscriptions(array('customer_id' => $user_id));
    if (!$subscriptions) {
      return false;
    }

    $subscription_ids = array();

    foreach ($subscriptions as $subscription) {
      $subscription_ids[] = $subscription->get_id();
    }

    return $subscription_ids;
  }

  public function get_user_membership_ids($user_id) {
    $memberships = wc_memberships_get_user_memberships($user_id);
    if (!$memberships) {
      return false;
    }

    $membership_ids = array();

    foreach ($memberships as $membership) {
      $membership_ids[] = $membership->get_id();
    }

    return $membership_ids;
  }

  public function get_user_woo_data($user_id) {
    if (is_plugin_active('woocommerce/woocommerce.php')) {
      $order_ids = $this->get_user_order_ids($user_id);
    }
    $subscription_ids = $this->get_user_subscription_ids($user_id);
    $membership_ids = $this->get_user_membership_ids($user_id);

  }

  public function map_user_data($source_user_id, $target_user_id) {
    $source_user_data = $this->wp_am_get_user_data($source_user_id);
    $target_user_data = $this->wp_am_get_user_data($target_user_id);

    if ((!$target_user_data) || (!$source_user_data)) {
      return (((!$target_user_data) && (!$source_user_data)) ? "Neither user could be found" : ((!$target_user_data) ? "Target user could not be found" : "Source user could not be found")); 
    }

    $mapped_data = array();

    $mapped_data['username'] = $target_user_data['username'];
    $mapped_data['email'] = $target_user_data['email'];
    $mapped_data['roles'] = $target_user_data['roles'];

    return $this->get_user_woo_data($target_user_id);
  }
}
