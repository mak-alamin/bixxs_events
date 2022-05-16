<?php

/**
 * Bixxs Events Employee Tickets
 */

defined('ABSPATH') || exit;

// ------------------
// 1. Register new endpoint (URL) for My Account page
// Note: Re-save Permalinks or it will give 404 error
function bixxs_events_add_employee_tickets_endpoint()
{
    add_rewrite_endpoint('employee-appointments', EP_ROOT | EP_PAGES);
}

add_action('init', 'bixxs_events_add_employee_tickets_endpoint');


// ------------------
// 2. Add new query var
function bixxs_events_employee_query_vars($vars)
{
    $vars[] = 'employee-appointments';
    return $vars;
}

add_filter('query_vars', 'bixxs_events_employee_query_vars', 0);


// ------------------
// 3. Insert the new endpoint into the My Account menu
function bixxs_events_add_employee_link_my_account($items)
{
    $ticket_items = ['employee-appointments' => __('Meine Termine')];

    return array_slice($items, 0, 3, true) + $ticket_items + array_slice($items, 3, count($items), true);
}

add_filter('woocommerce_account_menu_items', 'bixxs_events_add_employee_link_my_account');


// ------------------
// 4. Add content to the new tab
function bixxs_events_employee_my_account_content()
{
    bixxs_events_mitarbeitertermineFunc();
}

/**
 * @important-note	"add_action" must follow 'woocommerce_account_{your-endpoint-slug}_endpoint' format
 */
add_action('woocommerce_account_employee-appointments_endpoint', 'bixxs_events_employee_my_account_content');
