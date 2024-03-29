<?php

function bixxs_events_availability()
{
    if (!isset($_POST['date']) || !isset($_POST['product_id'])) {
        wp_send_json('Something went wrong.');
    }

    $date = sanitize_text_field($_POST['date']);
    $product_id = sanitize_text_field($_POST['product_id']);

    $timeslots = bixxs_events_get_timeslots($date, $product_id);

    wp_send_json($timeslots);
}
add_action('wp_ajax_bixxs_events_availability', 'bixxs_events_availability');
add_action('wp_ajax_nopriv_bixxs_events_availability', 'bixxs_events_availability');
