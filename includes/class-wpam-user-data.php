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

  public function get_user_orders($user_id) {
    $orders = wc_get_orders(array(
      'customer_id' => $user_id,
      'limit' => -1
    ));
    if (!$orders) {
      return false;
    }

    return $orders;
  }

  public function get_user_subscriptions($user_id) {
    $subscriptions = wcs_get_subscriptions(array(
      'customer_id' => $user_id,
      'limit' => -1
    ));
    if (!$subscriptions) {
      return false;
    }

    return $subscriptions;
  }

  public function get_user_memberships($user_id) {
    $memberships = wc_memberships_get_user_memberships($user_id);
    if (!$memberships) {
      return false;
    }

    return $memberships;
  }
}
