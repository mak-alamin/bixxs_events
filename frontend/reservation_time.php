<?php

/*
* Add Reservation Time to Single Product Page at Frontend
*/

//Show Time Slots on the single product page
add_action ( 'woocommerce_before_add_to_cart_button', 'bixxs_events_show_time_slots', 25 );
function bixxs_events_show_time_slots() {
    global $product;
    $post_id = $product->get_id();

    $ticket_template_id = get_post_meta( $post_id, 'bixxs_events_event_template', true );

    if ( ! $ticket_template_id || $product->get_type() != 'bixxs_events_product' ) {
        return;
    }

    $bixxs_events_start_time = get_post_meta($post_id, 'bixxs_events_start_time', true);
    $bixxs_events_end_time = get_post_meta($post_id, 'bixxs_events_end_time', true);

    echo '<p><label for="bixxs_events_reserve_time">Bitte wählen Sie ein Datum.</label> <br>';

    echo '<input id="bixxs_events_datetimepicker" type="text" name="bixxs_events_reserve_time" placeholder="z.B. '.date('d.m.Y').'" autocomplete="off" data-product="'. $post_id . '" data-start="'. $bixxs_events_start_time .'" data-end="'. $bixxs_events_end_time .'" required><br>';  // Time Format: h:i a

    echo '<div id="bixxs_events_notice"></div>';
    echo '<div id="bixxs_events_available_tickets" data-available="0">Verfügbare Tickets:</div>';

}

/**
 * Require "Reservation Time" before add to cart
 */
function bixxs_events_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {
    $ticket_template_id = get_post_meta( $product_id, 'bixxs_events_event_template', true );

    if ( ! $ticket_template_id || wc_get_product($product_id)->get_type() != 'bixxs_events_product' ) {

        return $passed;
    }

    if( empty( $_POST['bixxs_events_reserve_time'] ) ) {
        $passed = false;
        wc_add_notice( __( 'Bitte wählen Sie Ihre passende Zeit aus den verfügbaren Daten.', '' ), 'error' );
    }

    $now= time();
    $time_date = strtotime(sanitize_text_field( $_POST['bixxs_events_reserve_time'] ));

    if ($time_date < $now){
        wc_add_notice('Der Tag muss in der Zukunft liegen.', 'error');
        $passed = false;
    }
    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'bixxs_events_add_to_cart_validation', 10, 4 );

/**
 * Add "Reservation Time" to cart item data
 */
function bixxs_events_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
    $ticket_template_id = get_post_meta( $product_id, 'bixxs_events_event_template', true );

    if ( ! $ticket_template_id || wc_get_product($product_id)->get_type() != 'bixxs_events_product' ) {
        return $cart_item_data;
    }

    if( isset( $_POST['bixxs_events_reserve_time'] ) ) {
        $cart_item_data['bixxs_events_reserve_time'] = sanitize_text_field( $_POST['bixxs_events_reserve_time'] );
    }

    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'bixxs_events_add_cart_item_data', 10, 3 );

/**
 * Display "Reservation Time" in the cart
 */
function bixxs_events_get_item_data( $item_data, $cart_item_data ) {

    if( isset( $cart_item_data['bixxs_events_reserve_time'] ) ) {
        $item_data[] = array(
            'key'   => 'Reservierung Datum',
            'value' => wc_clean( $cart_item_data['bixxs_events_reserve_time'] )
        );
    }
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'bixxs_events_get_item_data', 10, 2 );

/**
 * Add "Reservation Time" to order
 */
function bixxs_events_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {

    if( isset( $values['bixxs_events_reserve_time'] ) ) {
        $item->update_meta_data('Reservierung Datum', $values['bixxs_events_reserve_time']);

        // Add reservation time to order
        $order_post_meta = get_post_meta($order, 'Reservierung Datum', true);

    }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'bixxs_events_checkout_create_order_line_item', 15, 4 );

/**
 * Add "Reservation Time" to emails
 */
function bixxs_events_order_item_name( $product_name, $item ) {

    if( isset( $item['bixxs_events_reserve_time'] ) ) {
      $product_name .= sprintf( '<p>%s: %s</p>', __( 'Reservation Time',BIXXS_EVENTS_TEXTDOMAIN ), esc_html( $item['bixxs_events_reserve_time'] ) );
    }

    return $product_name;
 }
 add_filter( 'woocommerce_order_item_name', 'bixxs_events_order_item_name', 10, 2 );

function bixxs_events_add_reservation_times_to_order($order, $data){
    $reservation_times = '';
    $items = $order->get_items();
    foreach ( $items as $item ) {

        $reservation_times .= $item->get_meta('Reservierung Datum') . ',';
    }

    $order->update_meta_data( 'Reservierung Datum', $reservation_times );

}
add_action('woocommerce_checkout_create_order', 'bixxs_events_add_reservation_times_to_order', 10, 2);


/**
 * Enqueue script
 */

function bixxs_events_enqueue_script(){
    wp_enqueue_script( 'bixxs_events_availability', plugin_dir_url( __FILE__ ) . '/js/availability.js', array( 'jquery' ) );
    wp_localize_script( 'bixxs_events_availability', 'bixxs_events_availability', array('ajaxurl'=>admin_url('admin-ajax.php')));


}
add_action( 'wp_enqueue_scripts', 'bixxs_events_enqueue_script');