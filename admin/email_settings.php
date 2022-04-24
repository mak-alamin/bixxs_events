<?php
add_action('admin_menu' , 'bixxs_events_addingbixxs_events_emailsettingsFunc');

function bixxs_events_addingbixxs_events_emailsettingsFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("E-Mail Einstellung" , BIXXS_EVENTS_TEXTDOMAIN) , __('E-Mail Einstellung', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'bixxs-events-emailsettings' , 'bixxs_events_emailsettingsFunc');
	}	
}

function bixxs_events_emailsettingsFunc(){

    $email_body =
        'Hallo [name],

Dies ist nur eine E-Mail, um Ihren Termin zu bestätigen.

Bestelldatum: [bestelldatum]
Termin der Reservierung: [ticketdatum]
Veranstalter: [veranstalter]
Ort der Veranstaltung: [ort_veranstaltung]
Ticketnummer: [ticketnummer]

Mit freundlichen Grüßen,
Ihre Freunde im Ticketshop Solutions Demo Shop';

    $email_body_reebok =
        'Hallo [name],

Dies ist nur eine E-Mail, für Ihre Umbuchung des Tickets: [ticketnummer]. 

Bestelldatum: [bestelldatum]
Neuer Termin der Reservierung: [ticketdatum]
Veranstalter: [veranstalter] 
Ort der Veranstaltung: [ort_veranstaltung]
Ticketnummer: [ticketnummer] 

Mit freundlichen Grüßen,
Ihre Freunde im Ticketshop Solutions Demo Shop';

    // Check required fields
    $mlx_options = get_option('bixxs_events_options');
    if (isset($mlx_options['email_settings'])) {
        $mlx_email_options = $mlx_options['email_settings'];
    }else{
        $mlx_email_options = array(
            'buy_ticket' => array(
                'active' => false,
                'subject' => 'Neues Ticket - [veranstalter]',
                'body'   => $email_body,
            ),
            'rebook_ticket' => array(
                'active' => false,
                'subject' => 'Ticket erfolgreich umgebucht',
                'body'   => $email_body_reebok,
            ),
        );

    }




    if (isset($_POST['save_email_settings'])){
//        $new_email_body = $_POST['email_body'];
//        $email_body = sanitize_textarea_field($new_email_body);

        $buy_tickets = array(
            'active' => isset($_POST['email_settings']['buy_ticket']['active']),
            'subject' => sanitize_text_field($_POST['email_settings']['buy_ticket']['subject']),
            'body'  => sanitize_textarea_field($_POST['email_settings']['buy_ticket']['body']),
        );

        $rebook_tickets = array(
            'active' => isset($_POST['email_settings']['rebook_ticket']['active']),
            'subject' => sanitize_text_field($_POST['email_settings']['rebook_ticket']['subject']),
            'body'  => sanitize_textarea_field($_POST['email_settings']['rebook_ticket']['body']),
        );

        $mlx_email_options['buy_ticket'] = $buy_tickets;
        $mlx_email_options['rebook_ticket'] = $rebook_tickets;

        $mlx_options['email_settings'] = $mlx_email_options;

        update_option('bixxs_events_options', $mlx_options);

    }


    ?>
    <div class="wrap">
        <h1>Email Einstellung</h1>
        <hr>

        <h2>Terminbestätigung , die E.Mail geht an den Kunden und den Admin</h2>
        <form action="" method="post">
            <input  type="checkbox" name="email_settings[buy_ticket][active]" <?php echo $mlx_email_options['buy_ticket']['active']? 'checked':'';?>>
            <label for="email_settings[buy_ticket][active]">E-Mail senden</label>
            <label for="email_settings[buy_ticket][subject]">Betreff</label>
            <input  type="text" name="email_settings[buy_ticket][subject]" value="<?php echo $mlx_email_options['buy_ticket']['subject'];?>"><br>
            <textarea name="email_settings[buy_ticket][body]" id="" cols="62" rows="12">
<?php echo $mlx_email_options['buy_ticket']['body']; ?>
        </textarea>


            <br><br><br>
            <h2>Termin Umbuchung , die E.Mail geht an den Kunden und den Admin</h2>
            <input  type="checkbox" name="email_settings[rebook_ticket][active]" <?php echo $mlx_email_options['rebook_ticket']['active']? 'checked':'';?>>
            <label for="email_settings[rebook_ticket][active]">E-Mail senden</label>
            <label for="email_settings[rebook_ticket][subject]">Betreff</label>
            <input  type="text" name="email_settings[rebook_ticket][subject]" value="<?php echo $mlx_email_options['buy_ticket']['subject'];?>"><br>
            <textarea name="email_settings[rebook_ticket][body]" id="" cols="62" rows="12">
<?php echo $mlx_email_options['rebook_ticket']['body'];?>
        </textarea>

            <br><br>
            <input type="submit" value="Speichern" name="save_email_settings" class="button-primary">
        </form>
    </div>



    <?php
}


function bixxs_events_send_email ($type, WC_Order_Item $item){
    error_log(print_r($item, true));
    error_log($type);

    $mlx_email_options = get_option('bixxs_events_options')['email_settings'];

    if (!$mlx_email_options[$type]['active'])
        return;

    $order = $item->get_order();

    $first_name = $order->get_billing_first_name();
    $last_name = $order->get_billing_last_name();

    global $wpdb;
    $wpdb->hide_errors();
    $tick_result = $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}bixxs_events WHERE id=%d", $item->get_product()->get_meta('bixxs_events_event_template') ));

    if ($tick_result){
        $veranstalter = trim($tick_result[0]->veranstalter);
        $ort_veranstaltung = trim($tick_result[0]->ort_veranstaltung);
    } else {
        $veranstalter = '';
        $ort_veranstaltung = '';
    }


    $ticket_number = '';
    $item_id = $item->get_id();
    if ($item->get_quantity() > 1){
        for ($i = 1; $i < $item->get_quantity(); $i++){
            $ticket_number .= $item_id . $i. ', ';
        }
        $ticket_number .= $item_id . $item->get_quantity();

    }else {
        $ticket_number = $item_id;
    }



    $replacements = array(
        '[name]' => $first_name . ' ' . $last_name,
        '[first_name]' => $first_name,
        '[last_name]' => $last_name,
        '[bestelldatum]' => $order->get_date_created()->date('d.m.Y'),
        '[ticketdatum]' => $item->get_meta('Reservierung Datum'),
        '[veranstalter]' => $veranstalter,
        '[ort_veranstaltung]' => $ort_veranstaltung,
        '[ticketnummer]' => $ticket_number,

    );

    $subject = $mlx_email_options[$type]['subject'];
    $body = $mlx_email_options[$type]['body'];


    // Replace Parameters
    foreach ($replacements as $key => $value){
        $subject = str_replace($key, $value, $subject);
        $body = str_replace($key, $value, $body);
    }

    wp_mail($order->get_billing_email(), $subject, $body);
    wp_mail(get_option('admin_email'), $subject, $body);


}

add_action( 'woocommerce_payment_complete', 'bixxs_events_send_initial_email' );
function bixxs_events_send_initial_email( $order_id ){
    $order = wc_get_order( $order_id );

    $items = $order->get_items();


    error_log(print_r($items, true));

    foreach ($items as $item){
        $order_item = new WC_Order_Item_Product($item->get_id());

        error_log(print_r($order_item->get_product()->get_type(), true));
        if ($order_item->get_product()->get_type() == 'bixxs_events_product')
            bixxs_events_send_email('buy_ticket', $item);
    }

}

