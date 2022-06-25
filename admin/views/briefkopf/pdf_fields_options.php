<?php

// Get options
$bixxs_events_options = get_option('bixxs_events_options');
if (isset($bixxs_events_options['guest_settings'])) {
    $bixxs_pdf_options = $bixxs_events_options['guest_settings'];
} else {
    $bixxs_pdf_options = array(
        'show_pdf_productname' => true,
        'show_pdf_order_date' => true,
        'show_pdf_price' => true,
        'show_pdf_reserve_time' => true,
        'show_pdf_veranstalter' => true,
        'show_pdf_ort_veranstalter' => true,
        'show_pdf_menge' => true,
        'show_pdf_ticketnumber' => true,
        'show_pdf_qrcode' => true,
    );
}

$show_pdf_productname = isset($bixxs_pdf_options['show_pdf_productname']) ? $bixxs_pdf_options['show_pdf_productname'] : true;

$show_pdf_order_date = isset($bixxs_pdf_options['show_pdf_order_date']) ? $bixxs_pdf_options['show_pdf_order_date'] : true;

$show_pdf_price = isset($bixxs_pdf_options['show_pdf_price']) ? $bixxs_pdf_options['show_pdf_price'] : true;

$show_pdf_reserve_time = isset($bixxs_pdf_options['show_pdf_reserve_time']) ? $bixxs_pdf_options['show_pdf_reserve_time'] : true;

$show_pdf_veranstalter = isset($bixxs_pdf_options['show_pdf_veranstalter']) ? $bixxs_pdf_options['show_pdf_veranstalter'] : true;

$show_pdf_ort_veranstalter = isset($bixxs_pdf_options['show_pdf_ort_veranstalter']) ? $bixxs_pdf_options['show_pdf_ort_veranstalter'] : true;

$show_pdf_menge = isset($bixxs_pdf_options['show_pdf_menge']) ? $bixxs_pdf_options['show_pdf_menge'] : true;

$show_pdf_ticketnumber = isset($bixxs_pdf_options['show_pdf_ticketnumber']) ? $bixxs_pdf_options['show_pdf_ticketnumber'] : true;

$show_pdf_qrcode = isset($bixxs_pdf_options['show_pdf_qrcode']) ? $bixxs_pdf_options['show_pdf_qrcode'] : true;
