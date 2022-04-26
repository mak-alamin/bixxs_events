<?php

/*
* Add Reservation Time to Single Product Page at Frontend
*/

//Show Time Slots on the single product page

add_action('woocommerce_before_add_to_cart_button', 'bixxs_events_show_addons', 25);
function bixxs_events_show_addons()
{
    global $product;
    $post_id = $product->get_id();

    $ticket_template_id = get_post_meta($post_id, 'bixxs_events_event_template', true);

    if (!$ticket_template_id || $product->get_type() != 'bixxs_events_product') {
        return;
    }

    wp_enqueue_script('bixxs_events_addons');
    wp_enqueue_style('bixxs_events_addons');


    // Render addon fields
    $addons_fields = json_decode($product->get_meta('bixxs_events_fields'), true);

    if (!$addons_fields)
        return;

    foreach ($addons_fields as $key => $addons_field) {
        echo '<div class="bixxs_events_addons_wrapper">';
        switch ($addons_field['selection']) {
            case 'number':
                echo '<label for=bixxs_events_addons[' . $key . ']">' . $addons_field['label'] . '</label></br>';
                echo '<input type="number" id="bixxs_events_addons[' . $key . ']" name="bixxs_events_addons[' . $key . ']" data-price="' . $addons_field['price_event'] . '" data-pprice="' . $addons_field['price_person'] . '" onchange="bixxs_events_calculate_sumary()">';
                break;
            case 'short':
                echo '<label for=bixxs_events_addons[' . $key . ']">' . $addons_field['label'] . '</label></br>';
                echo '<input type="text" id="bixxs_events_addons[' . $key . ']" name="bixxs_events_addons[' . $key . ']" data-price="' . $addons_field['price_event'] . '" data-pprice="' . $addons_field['price_person'] . '" onchange="bixxs_events_calculate_sumary()">';
                break;
            case 'long':
                echo '<label for=bixxs_events_addons[' . $key . ']">' . $addons_field['label'] . '</label></br>';
                echo '<textarea id="bixxs_events_addons[' . $key . ']" name="bixxs_events_addons[' . $key . ']" rows="4" cols="50" data-price="' . $addons_field['price_event'] . '" data-pprice="' . $addons_field['price_person'] . '" onchange="bixxs_events_calculate_sumary()"></textarea>';
                break;
            case 'dd':
                echo '<label for=bixxs_events_addons[' . $key . ']">' . $addons_field['label'] . '</label></br>';
                echo '<select id="bixxs_events_addons[' . $key . ']" name="bixxs_events_addons[' . $key . ']" data-price="' . $addons_field['price_event'] . '" data-pprice="' . $addons_field['price_person'] . '" onchange="bixxs_events_calculate_sumary()">';
                foreach ($addons_field['options'] as $option_key => $option) {
                    $value = $option['text'];
                    if ($value == '')
                        continue;

                    echo '<option value="' . $option_key . '" data-price="' . $option['price'] . '" data-pprice="' . $option['price_person'] . '">' . $value . '</option>';
                }
                echo '</select>';
                break;
            case 'mc':
                echo '<label for=bixxs_events_addons[' . $key . ']">' . $addons_field['label'] . '</label></br>';

                foreach ($addons_field['options'] as $option_key => $option) {
                    $value = $option['text'];
                    if ($value == '')
                        continue;

                    echo '<input type="checkbox" name="bixxs_events_addons[' . $key . '][' . $option_key . ']" value="' . $value . '" data-price="' . $option['price'] . '" data-pprice="' . $option['price_person'] . '" onchange="bixxs_events_calculate_sumary()">' . $value . '<br>';
                }

                break;
        }

        echo '</div>';
    }
}

add_action('woocommerce_before_add_to_cart_button', 'bixxs_events_show_addons_summary', 35);
function bixxs_events_show_addons_summary()
{
    global $product;
    $post_id = $product->get_id();

    $ticket_template_id = get_post_meta($post_id, 'bixxs_events_event_template', true);

    if (!$ticket_template_id || $product->get_type() != 'bixxs_events_product') {
        return;
    }

    $name_singular = get_post_meta($post_id, 'bixxs_events_label', true);
    $name_plural = get_post_meta($post_id, 'bixxs_events_label_plural', true);
    $price_person = get_post_meta($post_id, 'bixxs_events_price_per_person', true);
    $price_event = get_post_meta($post_id, 'bixxs_events_price_per_event', true);
    $class_price_event = '';
    $class_price_person = '';

    if ($price_person == '' || $price_person == 0) {
        $price_person = 0;
        $class_price_person = 'bixxs_events_hidden';
    }

    if ($price_event == '' || $price_event == 0) {
        $price_event = 0;
        $class_price_event = 'bixxs_events_hidden';
    }



?>
    <div id="bixxs_events_addons">
        <h4>Zusammenfassung</h4>
        <div id="bixxs_events_price_person" class="<?php echo $class_price_person; ?>" data-name-singular="<?php echo $name_singular; ?>" data-name-plural="<?php echo $name_plural; ?>" data-price-person="<?php echo $price_person; ?>">1 x <?php echo $name_singular . ' ' . number_format($price_person, 2, ",", "."), " €"; ?></div>
        <div id="bixxs_events_price_event" class="<?php echo $class_price_event; ?>" data-price-event="<?php echo $price_event; ?>">Preis: <?php echo number_format($price_event, 2, ",", "."), " €"; ?></div>
        <div id="bixxs_events_addons_summary">

        </div>
        <div id="bixxs_events_summary">Summe: <?php echo number_format($price_event + $price_person, 2, ",", "."), " €"; ?></div>
    </div>

<?php

}


add_filter('woocommerce_add_cart_item_data', 'bixxs_events_add_cart_item_data_addons', 15, 3);
function bixxs_events_add_cart_item_data_addons($cart_item_data, $product_id, $variation_id)
{
    $ticket_template_id = get_post_meta($product_id, 'bixxs_events_event_template', true);

    if (!$ticket_template_id || wc_get_product($product_id)->get_type() != 'bixxs_events_product') {
        return $cart_item_data;
    }

    if (isset($_POST['bixxs_events_addons'])) {

        $addon_fields = $_POST['bixxs_events_addons'];

        $sanitized_addon_fields = array();

        // get active clients
        foreach ($addon_fields as $key => $field) {

            if (is_array($field)) {
                $sanitized_array = array();

                foreach ($field as $array_key => $array_value) {
                    $sanitized_array[(int)$array_key] = sanitize_text_field($array_value);
                }

                $sanitized_addon_fields[(int)$key] = $sanitized_array;
                continue;
            }

            $sanitized_addon_fields[(int)$key] = sanitize_text_field($field);
        }

        $cart_item_data['bixxs_events_addons'] = json_encode($sanitized_addon_fields);
    }

    return $cart_item_data;
}

add_filter('woocommerce_get_item_data', 'bixxs_events_get_item_data_addons', 10, 2);
/**
 * Display "Reservation Time" in the cart
 */
function bixxs_events_get_item_data_addons($item_data, $cart_item_data)
{

    if (isset($cart_item_data['bixxs_events_addons'])) {

        $bixxs_events_addons = json_decode($cart_item_data['bixxs_events_addons'], true);

        $product = wc_get_product($cart_item_data['product_id']);

        $addon_fields = json_decode($product->get_meta('bixxs_events_fields'), true);


        foreach ($bixxs_events_addons as $key => $value) {

            if ($value == '')
                continue;

            if (isset($addon_fields[$key])) {
                $addon_field = $addon_fields[$key];

                if ($addon_field['selection'] == 'dd') {

                    $item_data[] = array(
                        'key' => $addon_field['label'],
                        'value' => $addon_field['options'][$value]['text'],
                    );
                } else if ($addon_field['selection'] == 'mc') {

                    $item_data[] = array(
                        'key' => $addon_field['label'],
                        'value' => implode(' ,',  $value),
                    );
                } else {
                    $item_data[] = array(
                        'key' => $addon_field['label'],
                        'value' => $value,
                    );
                }
            }
        }
    }
    return $item_data;
}

add_action('woocommerce_checkout_create_order_line_item', 'bixxs_events_checkout_create_order_line_item_addons', 10, 4);
function bixxs_events_checkout_create_order_line_item_addons($item, $cart_item_key, $values, $order)
{

    if (array_key_exists('bixxs_events_addons', $values)) {

        $item->update_meta_data('_bixxs_events_addons', $values['bixxs_events_addons']);

        // Add a snapshot of the fields
        if ($addon_fields = $values['data']->get_meta('bixxs_events_fields')) {
            $item->update_meta_data('_bixxs_events_fields', $addon_fields);
        }
    }
}


add_action('woocommerce_before_calculate_totals', 'bixxs_events_calculate_price', 99);
function bixxs_events_calculate_price($cart_object)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    if (did_action('woocommerce_before_calculate_totals') >= 2)
        return;

    foreach ($cart_object->get_cart() as $key => $item) {
        $product_id = $item['product_id'];
        $ticket_template_id = get_post_meta($product_id, 'bixxs_events_event_template', true);

        if (!$ticket_template_id && !isset($item['mlx_guests']))
            continue;

        if (wc_get_product($product_id)->get_type() != 'bixxs_events_product') {
            continue;
        }

        if (isset($item['bixxs_events_addons'])) {
            $bixxs_events_addons = json_decode($item['bixxs_events_addons'], true);
        } else {
            $bixxs_events_addons = array();
        }
        $addon_fields = json_decode(get_post_meta($product_id, 'bixxs_events_fields', true), true);

        $guests = json_decode($item['mlx_guests'], true);

        $total = 0;

        // add price per person and price per event
        $product = wc_get_product($product_id);

        $total += (float) $product->get_meta('bixxs_events_price_per_event');
        $total += (float) $product->get_meta('bixxs_events_price_per_person') * count($guests);

        foreach ($bixxs_events_addons as $addon_key => $value) {

            if ($value == '')
                continue;

            $addon_field = $addon_fields[$addon_key];

            if ($addon_field['selection'] == 'dd') {
                $total += (float) $addon_field['options'][$value]['price'];
                $total += (float) $addon_field['options'][$value]['price_person'] * count($guests);
            } else if ($addon_field['selection'] == 'mc') {
                foreach ($value as $option_key => $option) {
                    $total += (float)$addon_field['options'][$option_key]['price'];
                    $total += (float)$addon_field['options'][$option_key]['price_person'] * count($guests);
                }
            } else if ($addon_field['selection'] == 'number') {
                if ($value == '0')
                    continue;

                $total += (float) $addon_field['price_event'];
                $total += (float) $addon_field['price_person'] * (int)$value;
            } else {
                $total += (float) $addon_field['price_event'];
                $total += (float) $addon_field['price_person'] * count($guests);
            }
        }

        $item['data']->set_price($total / count($guests));

        WC()->cart->set_quantity($key, count($guests), true);
    }
}

add_action('wp_enqueue_scripts', 'bixxs_events_register_addons_js');
function bixxs_events_register_addons_js()
{
    wp_register_script('bixxs_events_addons', plugin_dir_url(__FILE__) . '/js/addons.js', array('jquery'), time(), true);
    wp_register_style('bixxs_events_addons', plugin_dir_url(__FILE__) . '/css/addons.css');
}
