<?php
add_action('admin_menu', 'bixxs_events_addingbixxs_events_emailsettingsFunc');

function bixxs_events_addingbixxs_events_emailsettingsFunc()
{
    $status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
    if ($status) {
        add_submenu_page("bixxs-events", __("E-Mail Einstellung", BIXXS_EVENTS_TEXTDOMAIN), __('E-Mail Einstellung', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-emailsettings', 'bixxs_events_emailsettingsFunc');
    }
}

function bixxs_events_emailsettingsFunc()
{
    $email_body =
        '<p>Hallo [name],</p>

        <p>Dies ist nur eine E-Mail, um Ihren Termin zu bestätigen.
</p>
<p>Bestelldatum: [bestelldatum] </p>
<p>Termin der Reservierung: [ticketdatum] </p>
<p>Veranstalter: [veranstalter] </p>
<p>Ort der Veranstaltung: [ort_veranstaltung] </p>
<p>Ticketnummer: [ticketnummer]</p>

<p>Mit freundlichen Grüßen,
Ihre Freunde im Ticketshop Solutions Demo Shop</p>';

    $email_body_reebok =
        '<p>Hallo [name], </p>

<p>Dies ist nur eine E-Mail, für Ihre Umbuchung des Tickets: [ticketnummer]. </p>

<p> Bestelldatum: [bestelldatum] </p>
<p>Neuer Termin der Reservierung: [ticketdatum]</p>
<p>Veranstalter: [veranstalter] </p>
<p>Ort der Veranstaltung: [ort_veranstaltung]</p>
<p>Ticketnummer: [ticketnummer] </p>

Mit freundlichen Grüßen,
Ihre Freunde im Ticketshop Solutions Demo Shop';

    $email_body_download = '<p> Dies ist nur eine Downloadbestätigung</p>,

    <p> Bestelldatum: [bestelldatum] </p>
    <p>Termin der Reservierung: [ticketdatum] </p>
<p>Veranstalter: [veranstalter] </p>
<p>Ort der Veranstaltung: [ort_veranstaltung]</p>
<p>Ticketnummer: [ticketnummer]</p>
<p>Download Ticket: [url_download_ticket]</p>

<p>Mit freundlichen Grüßen,
Ihre Freunde im Ticketshop Solutions Demo Shop</p>';


    // Check required fields
    $mlx_options = get_option('bixxs_events_options');
    if (isset($mlx_options['email_settings'])) {
        $mlx_email_options = $mlx_options['email_settings'];
    } else {
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
            'download_ticket' => array(
                'active' => false,
                'subject' => 'Downloadbestätigung',
                'body'   => $email_body_download,
            ),
        );
    }

    if (isset($_POST['save_email_settings'])) {
        $buy_tickets = array(
            'active' => isset($_POST['email_settings']['buy_ticket']['active']),

            'subject' => isset($_POST['email_settings']['buy_ticket']['subject']) ? sanitize_text_field($_POST['email_settings']['buy_ticket']['subject']) : 'Neues Ticket - [veranstalter]',

            'body'  => isset($_POST["email_settings_buy_ticket_body"]) ?  $_POST["email_settings_buy_ticket_body"] : $email_body,
        );

        $rebook_tickets = array(
            'active' => isset($_POST['email_settings']['rebook_ticket']['active']),

            'subject' => isset($_POST['email_settings']['rebook_ticket']['subject']) ? sanitize_text_field($_POST['email_settings']['rebook_ticket']['subject']) : 'Ticket erfolgreich umgebucht',

            'body'  => isset($_POST["email_settings_rebook_ticket_body"]) ?  $_POST["email_settings_rebook_ticket_body"] : $email_body_reebok,
        );

        $download_tickets = array(
            'active' => isset($_POST['email_settings']['download_ticket']['active']),

            'subject' => isset($_POST['email_settings']['download_ticket']['subject']) ? sanitize_text_field($_POST['email_settings']['download_ticket']['subject']) : 'Downloadbestätigung',

            'body'  => isset($_POST["email_settings_download_ticket_body"]) ?  $_POST["email_settings_download_ticket_body"] : $email_body_download,
        );

        $mlx_email_options['buy_ticket'] = $buy_tickets;
        $mlx_email_options['rebook_ticket'] = $rebook_tickets;
        $mlx_email_options['download_ticket'] = $download_tickets;

        $mlx_options['email_settings'] = $mlx_email_options;

        update_option('bixxs_events_options', $mlx_options);
    }

    require_once __DIR__ . '/views/email_settings/email_fields.php';
}

/**
 * Get QR Code Url
 */
function bixxs_events_email_setting_generate_qr_code_url($item_id, $guest_number, $ticket_template_id, $ticket_name, $order_date, $ticket_price, $veranstalter, $ort_veranstaltung, $qrcode_color, $guests)
{
    if (!class_exists('qrstr')) {
        include_once plugin_dir_path(__FILE__) . 'phpqrcode-master/qrlib.php';
    }
    if (file_exists(plugin_dir_path(__FILE__) . 'phpqrcode-master/index.php') && class_exists('qrstr')) {
        require plugin_dir_path(__FILE__) . 'phpqrcode-master/index.php';
    }

    $raw_svg = $qr_code_img[$guest_number - 1];

    $image_path = '/phpqrcode-master/temp/' . md5($item_id) . '.png';

    $image_file = __DIR__ . $image_path;

    $img = new \Imagick();
    $svg = file_get_contents($raw_svg);
    $img->readImageBlob($svg);
    $img->setImageFormat("png24");
    $img->writeImage($image_file);
    $img->clear();
    $img->destroy();

    $image_file = BIXXS_EVENTS_PLUGIN_DIR . substr($image_file, strpos($image_file, '/admin/phpqrcode-master/temp'));

    return $image_file;
}

function bixxs_events_send_email($type, WC_Order_Item $item, $guest_number = 1, $send_to_admin = true)
{
    error_log(print_r($item, true));
    error_log($type);

    $bixss_event_options = get_option('bixxs_events_options');

    $mlx_email_options = isset($bixss_event_options['email_settings']) ? $bixss_event_options['email_settings'] : [];

    if (empty($mlx_email_options) || !isset($mlx_email_options[$type]) || !$mlx_email_options[$type]['active']) {
        return;
    }

    $guests = json_decode($item->get_meta('_mlx_guests'), true);

    $guest_email = isset($guest['email']) ? $guest['email'] : '';

    $product_id = $item->get_product_id();

    $order = $item->get_order();
    $order_id = $order->get_id();

    $ticket_name = $item->get_name();
    $ticket_price = get_post_meta($product_id, '_regular_price', true);
    $order_date = $order->get_date_created('Y/m/d')->format('d.m.Y');

    $ticket_template_id = get_post_meta($product_id, 'bixxs_events_event_template', true);

    $first_name = $order->get_billing_first_name();
    $last_name = $order->get_billing_last_name();

    global $wpdb;
    $wpdb->hide_errors();
    $tick_result = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}bixxs_events WHERE id=%d",
        $item->get_product()->get_meta('bixxs_events_event_template')
    ));

    if ($tick_result) {
        $veranstalter = trim($tick_result[0]->veranstalter);
        $ort_veranstaltung = trim($tick_result[0]->ort_veranstaltung);
        $qrcode_color = trim($tick_result[0]->qrcode_color);
    } else {
        $veranstalter = '';
        $ort_veranstaltung = '';
    }

    $ticket_number = '';
    $item_id = $item->get_id();

    if ($guest_number > 1) {
        $ticket_number .= $item_id . $guest_number;
    } else {
        $ticket_number = $item_id;
    }

    $download_token = get_post_meta($order->get_id(), 'pdf_download_token', true);

    $download_ticket_url = sprintf("%s?action=pdf_download&order_id=%s&item_id=%s&guest_number=$guest_number&ticket_number=%s&download_token=%s", site_url(), $order_id, $item_id, $ticket_number, $download_token);

    require_once BIXXS_EVENTS_PLUGIN_DIR . '/admin/views/briefkopf/pdf_fields_options.php';

    if ($show_pdf_qrcode) {
        $qr_code_img = bixxs_events_email_setting_generate_qr_code_url($ticket_number, $guest_number, $ticket_template_id, $ticket_name, $order_date, $ticket_price, $veranstalter, $ort_veranstaltung, $qrcode_color, $guests);
    } else {
        $ean_generator = new Picqer\Barcode\BarcodeGeneratorJPG();

        $qr_code_img = BIXXS_EVENTS_PLUGIN_DIR . '/temp/' . $item_id . $ticket_name . $ticket_number . '.jpg';

        file_put_contents($qr_code_img, $ean_generator->getBarcode($ticket_number, $ean_generator::TYPE_CODE_128));
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
        '[url_download_ticket]' => '<a href="' . $download_ticket_url . '" target="_blank">Download</a>',
    );

    $subject = $mlx_email_options[$type]['subject'];
    $body = wpautop($mlx_email_options[$type]['body']);

    // Replace Parameters
    foreach ($replacements as $key => $value) {
        $subject = str_replace($key, $value, $subject);
        $body = str_replace($key, $value, $body);
    }

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . bloginfo('name') . ' <' . get_option('woocommerce_email_from_address') . '>'
    );

    $attachments = ($type == 'download_ticket') ? array($qr_code_img) : array();


    wp_mail($order->get_billing_email(), $subject, $body, $headers, $attachments);

    if ($send_to_admin) {
        wp_mail(get_option('admin_email'), $subject, $body, $headers, $attachments);
    }

    if (!empty($guest_email) && $guest_email != $order->get_billing_email()) {
        wp_mail($guest_email, $subject, $body, $headers, $attachments);
    }
}

add_action('woocommerce_payment_complete', 'bixxs_events_send_initial_email');
add_action('woocommerce_order_status_completed', 'bixxs_events_send_initial_email');
function bixxs_events_send_initial_email($order_id)
{
    $order = wc_get_order($order_id);
    $items = $order->get_items();

    foreach ($items as $item) {
        $order_item = new WC_Order_Item_Product($item->get_id());

        if ($order_item->get_product()->get_type() == 'bixxs_events_product') {
            $guests = json_decode($item->get_meta('_mlx_guests'), true);

            bixxs_events_send_email('buy_ticket', $item);

            foreach ($guests as $key => $guest) {
                bixxs_events_send_email('download_ticket', $item, $key, false);
            }
        }
    }
}
