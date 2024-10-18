<?php

class Wpam_Admin_Settings {

  private $plugin_name;
  private $version;

  public function __construct( $plugin_name, $version ) {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

    add_action('admin_init', array($this, 'wpam_settings_init'));

  }

  public function enqueue_scripts() {
    wp_enqueue_script( 'wpam_admin_settings', plugin_dir_url( __FILE__ ) . 'js/wpam-admin-settings.js', array( 'jquery' ), $this->version, true );
  }

  public function enqueue_styles() {
    wp_enqueue_style( 'wpam_admin_settings', plugin_dir_url( __FILE__ ) . 'css/wpam-admin-settings.css', array(), $this->version, 'all' );
  }

  public function wpam_options_page() {
    add_submenu_page(
      'options-general.php',
      'WP Account Merger Options',
      'Account Merge',
      'manage_options',
      'wpam_options',
      array( $this, 'wpam_display_page' )
    );
  }

  public function wpam_settings_init() {
    register_setting( 'general', 'wpam_select_target' );
    register_setting( 'general', 'wpam_select_all' );

    add_settings_section(
      'wpam_settings_section',
      'Account Merge Options',
      array( $this, 'wpam_settings_section_callback' ),
      'general'
    );

    add_settings_field(
      'wpam_slect_target_field',
      'Select Target Data',
      array( $this, 'wpam_select_target_field_callback' ),
      'general',
      'wpam_settings_section'
    );

    add_settings_field(
      'wpam_select_all_field',
      'Select All',
      array( $this, 'wpam_select_all_field_callback' ),
      'general',
      'wpam_settings_section'
    );
  }

  public function wpam_display_page() {
    ?>
    <div class="wrap">
      <h1>WP Account Merger Settings</h1>
      <form method="post" action="options.php">
        <?php
          settings_fields( 'wpam_settings' );
          do_settings_sections( 'wpam_options' );
          submit_button( 'Save Settings' );
        ?>
      </form>
    </div>
    <?php
  }

  public function wpam_settings_section_callback() {
  }

  public function wpam_select_target_field_callback() {
    $options = get_option( 'wpam_select_target' );
    ?>
    <fieldset>
      <label for="wpam_select_target">
        <input type="checkbox" name="wpam_select_target"  value="1"  <?php checked($options); ?>/>
        Select Target
      </label>
    </fieldset>
    <?php
  }

  public function wpam_select_all_field_callback() {
    $options = get_option( 'wpam_select_all' );
    ?>
    <fieldset>
      <label for="wpam_select_all">
        <input type="checkbox" name="wpam_select_all"  value="1"  <?php checked($options); ?>/>
        Select All
      </label>
    </fieldset>
    <?php
  }
}
