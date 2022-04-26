<?php

if (!function_exists('bixxs_events_delete_invalid_timeslots')) {
    function bixxs_events_delete_invalid_timeslots($timeslots)
    {
        $days = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];

        $correct_timeslots = [];
        if (!is_array($timeslots)) {
            foreach ($days as $day) {
                $day_key = sanitize_title($day);
                $correct_timeslots['tickets'][$day_key] = array();
                $correct_timeslots['timeslots'][$day_key] = array();
            }
            return $correct_timeslots;
        }


        foreach ($timeslots['timeslots'] as $day_key => $tts) {
            foreach ($tts as $index => $ts) {
                if (!empty($ts) && preg_match("/^((2[0-3])|[0-1]?[0-9]):([0-5][0-9])$/",  trim($ts))) {
                    $correct_timeslots['tickets'][$day_key][] = $timeslots['tickets'][$day_key][$index];
                    $correct_timeslots['timeslots'][$day_key][] = trim($ts);
                }
            }
        }

        return $correct_timeslots;
    }
}

// Saving data
add_action('woocommerce_process_product_meta', 'bixxs_events_save_timeslot_options');
function bixxs_events_save_timeslot_options($product_id)
{
    $product = wc_get_product($product_id);

    $keys = array(
        // 'available_monday',
        // 'available_tuesday',
        // 'available_wednesday',
        // 'available_thursday',
        // 'available_friday',
        // 'available_saturday',
        // 'available_sunday',
        'timeslots_selection'
    );

    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            if ($key == 'timeslots_selection') {
                $value = serialize(bixxs_events_delete_invalid_timeslots($_POST[$key]));
            } else {
                $value = sanitize_text_field($_POST[$key]);
            }

            $product->update_meta_data($key, $value);
            $product->save();
        }
    }

    // Variations
    if (isset($_POST['ticketmaster_variations'])) {
        $variations = $_POST['ticketmaster_variations'];

        $sanitized_variations = array();

        // Sanitize Values
        $i = 1;
        foreach ($variations as $variation) {
            // Check if price and name is set
            if (empty($variation['name']) || empty($variation['price']))
                continue;

            $sanitized_variations[$i]['name'] = sanitize_text_field($variation['name']);
            $sanitized_variations[$i]['price'] = sanitize_text_field($variation['price']);
            $sanitized_variations[$i]['sku'] = sanitize_text_field($variation['sku']);

            $i++;
        }

        if (count($sanitized_variations) < 1) {
            $product->delete_meta_data('ticketmaster_variations');
        } else {
            $product->update_meta_data('ticketmaster_variations', json_encode($sanitized_variations));
        }
        $product->save();
    } else {
        $product->delete_meta_data('ticketmaster_variations');
        $product->save();
    }
}


// check for options and create empty array
add_action('init', 'bixxs_events_check_option');
function bixxs_events_check_option()
{
    $bixxs_events_option = get_option('bixxs_events_options');
    if (!$bixxs_events_option)
        add_option('bixxs_events_options', array());
}


/**
 * Enqueue Scritps
 */
function bixss_enqueue_variation_script()
{
    wp_enqueue_script('bixxs_events_variation', plugin_dir_url(__FILE__) . 'admin/js/variation.js', '', '1.51');
}
add_action('admin_enqueue_scripts', 'bixss_enqueue_variation_script');
