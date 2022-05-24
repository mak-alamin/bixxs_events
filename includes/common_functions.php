<?php

// Turn off displaying unwanted meta data
add_filter('woocommerce_order_item_get_formatted_meta_data', 'bixxs_events_off_unwanted_order_item_meta_data', 10, 2);
function bixxs_events_off_unwanted_order_item_meta_data($formatted_meta, $item)
{
    foreach ($formatted_meta as $key => $meta) {
        if (in_array($meta->key, array('bixxs_events_item_employee'))) {
            unset($formatted_meta[$key]);
        }
    }

    return $formatted_meta;
}

function bixxs_events_get_orders_by_employee($employee_id, $date = '')
{
    global $wpdb;

    if (current_user_can('bixxs_event_employee') || $employee_id) {
        $item_id_sql = "SELECT `order_item_id` FROM `{$wpdb->prefix}woocommerce_order_itemmeta` WHERE `meta_key` = 'bixxs_events_item_employee' AND `meta_value` = $employee_id";
    } else {
        $item_id_sql = "SELECT `order_item_id` FROM `{$wpdb->prefix}woocommerce_order_itemmeta` WHERE `meta_key` = 'bixxs_events_item_employee'";
    }

    $order_items = $wpdb->get_results($item_id_sql);

    $order_item_ids = [];

    foreach ($order_items as $key => $item) {
        $order_item_ids[] = $item->order_item_id;
    }

    $order_item_ids = implode(',', $order_item_ids);

    $order_id_sql = "SELECT `order_id` FROM `{$wpdb->prefix}woocommerce_order_items` WHERE `order_item_id` IN ($order_item_ids)";

    $order_ids_arr = $wpdb->get_results($order_id_sql);

    $order_ids = [];

    foreach ($order_ids_arr as $key => $item) {
        $order_ids[] = $item->order_id;
    }

    $filtered_order_ids = $order_ids;

    if (!empty($date)) {
        $filter_date = date("d.m.Y", strtotime($date));

        foreach ($order_ids as $key => $id) {
            $order = wc_get_order($id);

            if (strpos($order->get_meta('Reservierung Datum'), $filter_date) === false) {
                unset($filtered_order_ids[$key]);
            }
        }
    }

    return $filtered_order_ids;
}

function bixxs_events_get_orders_by_product_id($product_id, $order_status = array('wc-processing', 'wc-pending', 'wc-completed'))
{
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode("','", $order_status) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

function bixxsEventsGetDayName($selectedDay)
{
    $weekday = "";
    switch ($selectedDay) {
        case 0:
            $weekday = "sonntag";
            break;
        case 1:
            $weekday = "montag";
            break;
        case 2:
            $weekday = "dienstag";
            break;
        case 3:
            $weekday = "mittwoch";
            break;
        case 4:
            $weekday = "donnerstag";
            break;
        case 5:
            $weekday = "freitag";
            break;
        case 6:
            $weekday = "samstag";
            break;
        default:
            $weekday = "";
            break;
    }

    return $weekday;
}

function bixxs_events_skip_order_stauses()
{
    $skip_statuses = apply_filters("bixxs_events_skip_order_stauses_filter", array('wc-refunded', 'wc-failed', 'wc-cancelled'));
    return array_diff(array_keys(wc_get_order_statuses()), $skip_statuses);
}

function bixxs_events_get_timeslots($date, $product_id)
{
    $available_timeslots = array();
    $timeslots = get_post_meta($product_id, 'timeslots_selection', true);
    $days = [
        'mon' => 'montag',
        'tue' => 'dienstag',
        'wed' => 'mittwoch',
        'thu' => 'donnerstag',
        'fri' => 'freitag',
        'sat' => 'samstag',
        'sun' => 'sonntag',
    ];

    if (empty($timeslots)) {
        return $available_timeslots;
    }

    if (is_string($timeslots)) {
        $timeslots = unserialize($timeslots);
    }

    $available_timeslots = $timeslots;

    $search_date = date("d.m.Y", strtotime($date));
    $all_guests = array();

    $day = strtolower(date('D', strtotime($date)));
    $day = $days[$day];

    $orders = wc_get_orders(array(
        'limit'        => -1,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'meta_key'     => 'Reservierung Datum',
        'meta_value'   => $search_date,
        'meta_compare' => 'LIKE',
        'status' => bixxs_events_skip_order_stauses()
    ));


    if (empty($orders)) {
        return $available_timeslots;
    }

    foreach ($orders as $order) {
        $items = $order->get_items();

        foreach ($items as $item_id => $item) {

            if ($product_id && $item->get_product_id() != $product_id) {
                continue;
            }

            if ($item->get_meta('Reservierung Datum') == $search_date) {

                $reserved_time = $item->get_meta("Reservierung Zeit");

                if (isset($available_timeslots['timeslots'][$day])) {

                    $reserved_time_index = array_search($reserved_time, $available_timeslots['timeslots'][$day]);

                    $available_tickets = $available_timeslots['tickets'][$day][$reserved_time_index];

                    $no_of_guests_booked = 1;

                    $guests = $item->get_meta('_mlx_guests');

                    if (!empty($guests)) {
                        $guests = @json_decode($guests, true);

                        if (!empty($guests) && count($guests) > 0) {
                            $no_of_guests_booked = count($guests);
                        }
                    }

                    $available_tickets = $available_tickets - $no_of_guests_booked;

                    $available_timeslots['tickets'][$day][$reserved_time_index] = $available_tickets;

                    if ($available_tickets < 0) {
                        unset($available_timeslots['tickets'][$day][$reserved_time_index]);
                        unset($available_timeslots['timeslots'][$day][$reserved_time_index]);
                    }
                }
            }
        }
    }

    return [
        'timeslots' => array_values($available_timeslots['timeslots'][$day]),
        'available_tickets' => $available_timeslots['tickets'][$day],
    ];
}
