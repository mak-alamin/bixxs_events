<?php

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

            wp_send_json($item);

            if ($product_id && $item->get_product_id() != $product_id) {
                continue;
            }

            if ($item->get_meta('Reservierung Datum') == $search_date) {

                $reserved_time = $item->get_meta("Reservierungszeit");

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
