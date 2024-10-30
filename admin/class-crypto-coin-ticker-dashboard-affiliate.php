<?php

/**
 * Add the Crypto Wallet Calculator widget to the dashboard.
 */
add_action( 'wp_dashboard_setup', 'ccticker_add_dash_affiliate_widget' );
function ccticker_add_dash_affiliate_widget() {
  if ( current_user_can( 'manage_options' ) ) {
    add_meta_box('ccticker_dash_affiliate_widget', 'Zwaply Affiliate Panel', 'ccticker_dash_affiliate_widget_function', 'dashboard', 'side', 'high');
  }
}

/**
 * Output the contents of our Crypto Wallet widget.
 */
function ccticker_dash_affiliate_widget_function() {
  $scrolling = 'yes';
  include_once 'partials/crypto-coin-ticker-affiliate-panel.php';
}
