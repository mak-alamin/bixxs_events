<?php

/*
* Add guest selection for tickets
*/

//Show guest selection on product page
add_action('woocommerce_before_add_to_cart_button', 'bixxs_events_show_guest_selection', 30);
function bixxs_events_show_guest_selection()
{
    global $product;
    $post_id = $product->get_id();

    $ticket_template_id = get_post_meta($post_id, 'bixxs_events_event_template', true);

    if (!$ticket_template_id || $product->get_type() != 'bixxs_events_product') {
        return;
    }

    // Enqueue script
    wp_enqueue_script('bixxs_events_guestlist');
    wp_enqueue_style('bixxs_events_guestlist');

    // Get options
    $mlx_options = get_option('bixxs_events_options');
    if (isset($mlx_options['guest_settings'])) {
        $mlx_guest_options = $mlx_options['guest_settings'];
    } else {
        $mlx_guest_options = array(
            'name' => true,
            'telephone' => true,
            'email' =>  true,
            'street' => true,
            'zipcity' => true,
            'max_guests' => 5,
        );
    }
    $max_guests = get_post_meta($post_id, 'bixxs_events_max_guests', true);

    $name = isset($mlx_guest_options['name']) && (bool)$mlx_guest_options['name'] ? 'event-master-required' : '';
    $telephone = isset($mlx_guest_options['telephone']) && (bool)$mlx_guest_options['telephone'] ? 'event-master-required' : '';
    $email = isset($mlx_guest_options['email']) && (bool)$mlx_guest_options['email'] ? 'event-master-required' : '';
    $street = isset($mlx_guest_options['street']) && (bool)$mlx_guest_options['street'] ? 'event-master-required' : '';
    $zipcity = isset($mlx_guest_options['zipcity']) && (bool)$mlx_guest_options['zipcity'] ? 'event-master-required' : '';

    $guest_selection = '<div id="mlx_guest_selection" class="mlx_guest_selection" data-max-guests="' . $max_guests . '" data-guests="1">';

    $name_singular = get_post_meta($post_id, 'bixxs_events_label', true);


    $customer = new WC_Customer(get_current_user_id());

    $guest_selection .= '
           
 <div id="mlx_guest_1">
	
    <details open>
		<summary>
			<h2 data-label="' . $name_singular . '">Ticket ' . $name_singular . ' </h2>
			<div class="event-master-delete-button" onclick="bixxs_events_delete_guest(\'mlx_guest_1\')">
			    <i class="fa fa-trash-alt"></i>
            </div>
		</summary>
		    <div class="event-master-guest-input">
                <div class="' . $name . '" >
                    <label for="mlx_guests[1][first_name]">Vorname</label>
                    <input type="text" name="mlx_guests[1][first_name]" value="' . $customer->get_billing_first_name() . '" ' . ($name ? 'required' : '') . '/>  
                </div>
                <div class="' . $name . '">
                    <label for="mlx_guests[1][last_name]">Nachname</label>
                    <input type="text" name="mlx_guests[1][last_name]" value="' . $customer->get_billing_last_name() . '" ' . ($name ? 'required' : '') . '/>    
                </div>  
                <div class="' . $telephone . '">
                    <label for="mlx_guests[1][telephone]">Telefonnummer</label>
                    <input type="tel" name="mlx_guests[1][telephone]" value="' . $customer->get_billing_phone() . '" ' . ($telephone ? 'required' : '') . '/>    
                </div>
                <div class="' . $email . '">
                    <label for="mlx_guests[1][email]">E-Mail</label>
                    <input type="email" name="mlx_guests[1][email]" value="' . $customer->get_billing_email() . '" ' . ($email ? 'required' : '') . '/>    
                </div>
                <div class="' . $street . '">
                    <label for="mlx_guests[1][street]">Straße, Hausnummer</label>
                    <input type="text" name="mlx_guests[1][street]" value="' . $customer->get_billing_address() . '" ' . ($street ? 'required' : '') . '/>    
                </div>
                <div class="' . $zipcity . '">
                    <label for="mlx_guests[1][zip]">PLZ</label>
                    <input type="text" name="mlx_guests[1][zip]" value="' . $customer->get_billing_postcode() . '" ' . ($zipcity ? 'required' : '') . '/>   
                </div>
                <div class="' . $zipcity . '">
                    <label for="mlx_guests[1][city]">Ort</label>
                    <input type="text" name="mlx_guests[1][city]" value="' . $customer->get_billing_city() . '" ' . ($zipcity ? 'required' : '') . '/>    
                </div>';


    // TODO: Remove all ticketmaster_variations
    $variations = json_decode($product->get_meta('ticketmaster_variations'), true);

    if ($variations) {
        $guest_selection .= '<div><label for="mlx_guests[1][variation]">Ticket</label> <select name="mlx_guests[1][variation]">';

        foreach ($variations as $key => $variation) {
            $guest_selection .= '<option value="' . $key . '">' .  $variation['name'] . ' ' . $variation['price'] . ' € </option>';
        }

        $guest_selection .= '</select></div>';
    }



    $guest_selection .= '</div>
            <div class="event-master-required-info">
                <p>(* Pflichtangaben)</p>
            </div>
    </details>
</div>';

    $guest_selection .= '
       <!-- </div><p><div>
	<button type="button" onclick="bixxs_events_add_guest()">+ ' . $product->get_meta('bixxs_events_label') . ' hinzufügen</button>
        </p></div> -->
    ';

    echo $guest_selection;
}


add_filter('woocommerce_add_cart_item_data', 'bixxs_events_add_item_data', 10, 3);

function bixxs_events_add_item_data($cart_item_data, $product_id, $variation_id)
{

    if (isset($_REQUEST['mlx_guests'])) {

        $mlx_guests = $_REQUEST['mlx_guests'];
        $mlx_active_guests = array();
        $counter = 1;

        // get active clients
        foreach ($mlx_guests as $guest) {
            //sanitize guest
            $sanitized_guest = array();
            foreach ($guest as $key => $val) {
                $sanitized_guest[$key] = sanitize_text_field($val);
            }

            $mlx_active_guests[$counter] = $sanitized_guest;
            $counter++;
        }

        $cart_item_data['mlx_guests'] = json_encode($mlx_active_guests);
    }

    return $cart_item_data;
}


add_filter('woocommerce_get_item_data', 'bixxs_events_add_item_meta', 10, 2);

function bixxs_events_add_item_meta($item_data, $cart_item)
{

    if ($cart_item['data']->get_type() != 'bixxs_events_product')
        return $item_data;

    if (array_key_exists('mlx_guests', $cart_item)) {
        $custom_details = $cart_item['mlx_guests'];
        $guests = json_decode($custom_details, true);

        $label = get_post_meta($cart_item['product_id'], 'bixxs_events_label', true);

        foreach ($guests as $key => $guest) {
            if (!isset($guest['first_name']))
                $guest['first_name'] = '';
            if (!isset($guest['last_name']))
                $guest['last_name'] = '';

            $item_data[] = array(
                'key'   => $label . ' ' . $key,
                'value' => $guest['first_name'] . ' ' . $guest['last_name'],
            );
        }
    }

    return $item_data;
}


add_action('woocommerce_checkout_create_order_line_item', 'bixxs_events_add_custom_order_line_item_meta', 15, 4);

function bixxs_events_add_custom_order_line_item_meta($item, $cart_item_key, $values, $order)
{

    if (array_key_exists('mlx_guests', $values)) {
        $item->update_meta_data('_mlx_guests', $values['mlx_guests']);
    }
}



// Hide quantity field from ticket booking
add_filter('woocommerce_is_sold_individually', 'bixxs_events_remove_quantity_field', 10, 2);
function bixxs_events_remove_quantity_field($return, $product)
{


    $ticket_template_id = get_post_meta($product->get_id(), 'bixxs_events_event_template', true);

    if ($ticket_template_id) {
        return true;
    }

    return $return;
}


add_filter('woocommerce_cart_item_quantity', 'bixxs_events_custom_checkout_cart_item_name', 10, 2);
// Display amount guest in quantities
function bixxs_events_custom_checkout_cart_item_name($item_qty, $cart_item_key)
{

    $cart_item = WC()->cart->get_cart_item($cart_item_key);

    if (!isset($cart_item['mlx_guests']))
        return $item_qty;

    $guests = json_decode($cart_item['mlx_guests'], true);

    $product_id = $cart_item['product_id'];

    if (count($guests) == 1)
        return '1 ' . get_post_meta($product_id, 'bixxs_events_label', true);

    return count($guests) . ' ' . get_post_meta($product_id, 'bixxs_events_label_plural', true);
}

/**
 * Check required guest data
 */
function bixxs_events_guest_validation($passed, $product_id, $quantity, $variation_id = null)
{
    $ticket_template_id = get_post_meta($product_id, 'bixxs_events_event_template', true);

    if (!$ticket_template_id || wc_get_product($product_id)->get_type() != 'bixxs_events_product') {
        return $passed;
    }

    if (empty($_POST['mlx_guests'])) {
        wc_add_notice(__('Das Ticket konnte nicht hinzugefügt werden.', ''), 'error');
        return false;
    }

    // Check required fields
    $mlx_options = get_option('bixxs_events_options');
    if (isset($mlx_options['guest_settings'])) {
        $mlx_guest_options = $mlx_options['guest_settings'];
    } else {
        $mlx_guest_options = array(
            'name' => true,
            'telephone' => true,
            'email' =>  true,
            'street' => true,
            'zipcity' => true,
            'max_guests' => 5,
        );
    }

    // separate combined fields
    $mlx_guest_options['first_name'] = $mlx_guest_options['name'];
    $mlx_guest_options['last_name'] = $mlx_guest_options['name'];
    $mlx_guest_options['zip'] = $mlx_guest_options['zipcity'];
    $mlx_guest_options['city'] = $mlx_guest_options['zipcity'];
    $mlx_guest_options['variation'] = false;


    $guest_list = $_POST['mlx_guests'];

    foreach ($guest_list as $guest) {
        foreach ($guest as $key => $value) {
            if ($mlx_guest_options[$key]) {
                if (strlen(preg_replace('/\s/', '', $value)) == 0) {
                    wc_add_notice('Bitte füllen Sie alle Pflichtfelder aus.', 'error');
                    return false;
                }
            }
        }
    }


    // Check availability
    if (isset($_POST['bixxs_events_reserve_date']) && !empty($_POST['bixxs_events_reserve_time'])) {

        $date = $_POST['bixxs_events_reserve_date'];
        $time = $_POST['bixxs_events_reserve_time'];

        $dayofweek = bixxsEventsGetDayName(date('w', strtotime($date)));

        $product = wc_get_product($product_id);

        $timeslotsData = bixxs_events_get_timeslots($date, $product_id);

        if (isset($timeslotsData['available_tickets']) && !empty($timeslotsData['available_tickets']) && !empty($timeslotsData['timeslots'])) {

            if (isset($timeslotsData['timeslots'])) {
                $index = array_search($time, $timeslotsData['timeslots']);
            } else {
                $index = '';
            }

            $available_tickets = 0;
            if (isset($timeslotsData['available_tickets'][$index])) {
                $available_tickets = $timeslotsData['available_tickets'][$index];
            }

            if ($available_tickets < count($guest_list)) {
                $passed = false;
                wc_add_notice('Es sind leider nicht genügend Tickets verfügbar', 'error');
            }
        } else if (isset($timeslotsData['tickets']) && !empty($timeslotsData['tickets']) && !empty($timeslotsData['timeslots'])) {

            if (isset($timeslotsData['timeslots'][$dayofweek])) {
                $index = array_search($time, $timeslotsData['timeslots'][$dayofweek]);
            } else {
                $index = '';
            }

            $available_tickets = 0;
            if (isset($timeslotsData['tickets'][$dayofweek][$index])) {
                $available_tickets = $timeslotsData['tickets'][$dayofweek][$index];
            }

            if ($available_tickets < count($guest_list)) {
                $passed = false;
                wc_add_notice('Es sind leider nicht genügend Tickets verfügbar', 'error');
            }
        } else {

            $passed = false;
            wc_add_notice('Es sind leider nicht genügend Tickets verfügbar', 'error');
        }
    }

    return $passed;
}
add_filter('woocommerce_add_to_cart_validation', 'bixxs_events_guest_validation', 10, 4);

function bixxs_events_register_guest_list_js()
{
    wp_register_script('bixxs_events_guestlist', plugin_dir_url(__FILE__) . '/js/guestlist.js');
    wp_register_style('bixxs_events_guestlist', plugin_dir_url(__FILE__) . '/css/guestlist.css');
}
add_action('wp_enqueue_scripts', 'bixxs_events_register_guest_list_js');
