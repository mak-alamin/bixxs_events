<?php

function bixxs_events_render_employee_pdf($all_guests)
{
    //format date
    $format_date = '(alle)';
    if (!empty($_REQUEST['filter_by_date'])) {
        $format_date = date("d.m.Y", strtotime($_REQUEST['filter_by_date']));
    }

    $ticketmaster_options = get_option('bixxs_events_options');
    if (isset($ticketmaster_options['general_settings'])) {
        $ticketmaster_general_options = $ticketmaster_options['general_settings'];
    } else {
        $ticketmaster_general_options = array(
            'logo' => '',
            'heading' => '',
            'info' => '',
            'additional_info' => '',
            'footer' => array(
                1 => '',
                2 => '',
                3 => '',
            ),
        );
    }

    $output = '';
    // Add header
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename=\"mitarbeitertermine.pdf\"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');

    // Add style
    $output .= '
        <style>
            h1 {
                font-size: 24px;   
                font-family: "DejaVu Sans"; 
                text-align: center;
                 vertical-align:top;
            }
            .header{
                margin-top: 20px;
                height: 100px;
                font-size: 12px;
                vertical-align:top;
                display: inline-block;

            }
            
            .header > div{
                overflow: hidden;
                height: 100px;
                width: 32%;
                display: inline-block;
                font-size: 10px;
            }
            
            .logo img {
                max-width: 80%;
            }
            
            .content {
                display: block;
                width: 100%;
                float: none;
            }
            
            table td {
            font-size: 12px;
            font-family: "DejaVu Sans";
          
  }
            
            table thead td {
            font-weight: 600;
	  font-size: 12px;
            font-family: "DejaVu Sans";
            }
        </style>    
    ';

    // Add header

    $output .= '<div class="header">
                    <div class="logo">
                        <img src="' . $ticketmaster_general_options['logo'] . '">
                    </div>
                                    </div>';

    $output .= '<div class="content"><h1>Mitarbeiter Termine - ' . $format_date . '</h1>';

    // Render guests
    $output .= '
        <table border="0" width="90%" cellspacing="10" cellpadding="5">
        <thead>
        <tr>
            <td>Ticketnummer</td>
            <td>Name / Vorname</td>
            <td>Datum Uhrzeit</td>
            <td>Produkt</td>
        </tr>
        </thead>
        <tbody>    
    ';

    if (!empty($all_guests)) {
        foreach ($all_guests as $guest) {
            $output .= '<tr><td>' . $guest['ticket_id'] . '</td><td>' . $guest['first_name'] . ' ' . $guest['last_name'] .
                '</td><td>' .  $guest['reserve_date'] . ', ' . $guest['reserve_time'] . '</td><td>' .
                $guest['product_name'] . '</td></tr>';
        }
    } else {
        $output .= "<tr><td>Keine Tickets gefunden</td></tr>";
    }
    $output .= '</tbody></div>';
    return $output;
}
