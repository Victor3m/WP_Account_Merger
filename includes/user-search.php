<?php

require_once('/Users/josiah.hester/Local Sites/test-wpaccountmerger/app/public/wp-load.php');

$search_query = $_GET['user'];

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

echo json_encode($results);
