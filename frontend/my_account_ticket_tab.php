<?php
/**
 * Tickets and Vouchers My Orders
 */

defined( 'ABSPATH' ) || exit;

/**
 * 1. Register new endpoint slug to use for My Account page
 */

/**
 * @important-note	Resave Permalinks or it will give 404 error
 */
function bixxs_events_add_endpoint() {
    add_rewrite_endpoint( 'events', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'bixxs_events_add_endpoint' );
  
  
/**
 * 2. Add new query var
 */
  
function bixxs_events_query_vars( $vars ) {
    $vars[] = 'events';
    return $vars;
}


// add_filter( 'woocommerce_get_query_vars', 'bixxs_events_query_vars', 0 );
  
  
/**
 * 3. Insert the new endpoint into the My Account menu
 */
  
function bixxs_events_add_link_my_account( $items ) {
    $ticket_items = ['events' => __('Veranstaltungen')];

    return array_slice( $items, 0, 3, true ) + $ticket_items + array_slice( $items, 3, count($items), true );
}
  
add_filter( 'woocommerce_account_menu_items', 'bixxs_events_add_link_my_account' );


function bixxs_events_add_view_order_capability()
{
    $role = get_role('administrator');
    // add the 'view_order' capability
    $role->add_cap('view_order', true);
}
add_action('init', 'bixxs_events_add_view_order_capability', 11);

  
/**
 * 4. Add content to the new endpoint
 */
  
function bixxs_events_content() {
  require_once plugin_dir_path(__FILE__). 'views/tickets_my_order_table.php';
}

/**
 * @important-note	"add_action" must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
 */
add_action( 'woocommerce_account_events_endpoint', 'bixxs_events_content' );
