<?php

add_action( 'admin_enqueue_scripts', 'wpam_enqueue_scripts' );
function wpam_enqueue_scripts( $hook ) {

  wp_enqueue_script( 
    'user-search', 
    plugin_dir_url( __FILE__ ) . 'user-search.js',
    array( 'jquery' ), 
    '1.0.0',
    true
  );

  wp_localize_script(
    'user-search',
    'wpam_search_obj',
    array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('wpam_search_nonce') 
    )
  );
}

add_action('wp_ajax_wpam_user_search', 'wpam_search_handler');
function wpam_search_handler() {
  check_ajax_referer('wpam_search_nonce');

  $search_query = $_POST['user'];

  $args = array(
    'search' => '*' . esc_attr($search_query) . '*',
    'search_columns' => array('ID', 'user_login', 'user_nicename', 'user_email'),
    'number' => 10
  );

  $users = get_users($args);

  $results = array();

  foreach ($users as $user) {
    $results[] = array(
      'id' => $user->ID,
      'user_login' => $user->user_login,
      'user_nicename' => $user->user_nicename,
      'user_email' => $user->user_email
    );
  }

  wp_send_json($results, 200);
}
