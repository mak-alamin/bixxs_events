<?php    
/*
 * PHP QR Code encoder
 */

//  if (!defined('ABSPATH')) {
//      exit;
//  }
    
// echo 'Quantity: ' . $quantity;


$qr_code_img = [];

for ($i=1; $i <= $quantity; $i++) { 
    
    if( $quantity == 1 ){
 
        $code_id = $item_id;
    
    } else {
        $code_id = strval($item_id) . strval($i);
    }

    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = plugin_dir_url(__FILE__) . 'temp/';

    
    //ofcourse we need rights to create temp dir
    if ( !file_exists($PNG_TEMP_DIR) ){
        mkdir($PNG_TEMP_DIR);
    }
    
    
    // $filename = $PNG_TEMP_DIR.'test.png';

    // user data
    $filename = $PNG_TEMP_DIR.'qrcode_'. $i . md5($item_id).'.svg';

    if (isset($_POST['print_pdf_template'])) {
        $user_data = array(
            'Firma: ' . $this->set_cname,
            'Ort: ' . $this->set_city,
            'Land: ' . $this->set_country
        );
    } else {
        if ( $ticket_template_id ) {
            $user_data = array(
                'Name: ' . $ticket_name,
                'Veranstalter: ' . $veranstalter,
                'Ort der Veranstaltung: ' . $ort_veranstaltung ,
                'Bestelldatum: ' . $order_date,
                'Menge: 1',
                'Preis: ' . $ticket_price . ' €',
                'Code-ID: ' . $code_id
            );
        } else {
            $user_data = array(
                'Name: ' . $ticket_name,
                'Bestelldatum: ' . $order_date,
                'Menge: 1',
                'Preis: ' . $ticket_price . ' €',
                'Code-ID: ' . $code_id
            );
        }
        
    }
    
    $user_data = implode(', ', $user_data);
    
    $backColor = hexdec('FFFFFF');
    $qrcode_color = !empty($qrcode_color) ? ltrim($qrcode_color, '#') : '000000';
    $foreColor = hexdec($qrcode_color);

    QRcode::svg($user_data , $filename, 'L', 5, 2, false, $backColor, $foreColor);    

    
    //generated svg QR code image
    $qr_code_img[] = $PNG_WEB_DIR.basename($filename);
}

// echo "<pre>";
// print_r($qr_code_img);