<?php
add_action('admin_menu', 'bixxs_events_addingbixxs_events_extraticketsFunc');

function bixxs_events_addingbixxs_events_extraticketsFunc()
{
    $status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
    if ($status) {
        add_submenu_page("bixxs-events", __("Extra Tickets", BIXXS_EVENTS_TEXTDOMAIN), __('Extra Tickets', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-extratickets', 'bixxs_events_extraticketsFunc');
    }
}

function bixxs_events_extraticketsFunc()
{
    // Get options
    $mlx_options = get_option('bixxs_events_options');
    if (isset($mlx_options['guest_settings'])) {
        $mlx_guest_options = $mlx_options['guest_settings'];
    } else {
        $mlx_guest_options = array(
            'show_kalendar' => true,
            'no_kalendar' => true,
            'name' => true,
            'show_name' => true,
            'telephone' => true,
            'show_telephone' => true,
            'email' =>  true,
            'show_email' =>  true,
            'street' => true,
            'show_street' => true,
            'show_zipcity' => true,
        );
    }

    if (isset($_POST['bixxs_events_extra_tickets'])) {
        // parse and sanitize user input
        $mlx_guest_options = array(
            'show_kalendar' => isset($_POST['show_kalendar']),
            'no_kalendar' => isset($_POST['no_kalendar']),
            'name' => isset($_POST['name']),
            'show_name' => isset($_POST['show_name']),
            'telephone' => isset($_POST['telephone']),
            'show_telephone' => isset($_POST['show_telephone']),
            'email' =>  isset($_POST['email']),
            'show_email' =>  isset($_POST['show_email']),
            'street' => isset($_POST['street']),
            'show_street' => isset($_POST['show_street']),
            'zipcity' => isset($_POST['zipcity']),
            'show_zipcity' => isset($_POST['show_zipcity']),
        );

        // save updated options
        $mlx_options['guest_settings'] = $mlx_guest_options;
        update_option('bixxs_events_options', $mlx_options);
    }

    $show_kalendar = isset($mlx_guest_options['show_kalendar']) && (bool)$mlx_guest_options['show_kalendar'] ? ' checked' : '';

    $no_kalendar = isset($mlx_guest_options['no_kalendar']) && (bool)$mlx_guest_options['no_kalendar'] ? ' checked' : '';

    $name = isset($mlx_guest_options['name']) && (bool)$mlx_guest_options['name'] ? ' checked' : '';
    $show_name = isset($mlx_guest_options['show_name']) && (bool)$mlx_guest_options['show_name'] ? ' checked' : '';

    $telephone = isset($mlx_guest_options['telephone']) && (bool)$mlx_guest_options['telephone'] ? ' checked' : '';
    $show_telephone = isset($mlx_guest_options['show_telephone']) && (bool)$mlx_guest_options['show_telephone'] ? ' checked' : '';

    $email = isset($mlx_guest_options['email']) && (bool)$mlx_guest_options['email'] ? ' checked' : '';
    $show_email = isset($mlx_guest_options['show_email']) && (bool)$mlx_guest_options['show_email'] ? ' checked' : '';

    $street = isset($mlx_guest_options['street']) && (bool)$mlx_guest_options['street'] ? ' checked' : '';
    $show_street = isset($mlx_guest_options['show_street']) && (bool)$mlx_guest_options['show_street'] ? ' checked' : '';

    $zipcity = isset($mlx_guest_options['zipcity']) && (bool)$mlx_guest_options['zipcity'] ? ' checked' : '';
    $show_zipcity = isset($mlx_guest_options['show_zipcity']) && (bool)$mlx_guest_options['show_zipcity'] ? ' checked' : '';


    echo '<div class="wrap">
    <h1>Extra Gast Einstellungen | Kalender</h1>
    <hr>

    <form action="" method="POST" name="save_extra_settings">

        <table cellspacing="10" cellpadding="5">
            <tr>
                <td><b><u>Felder</b></u></td>
                <td><b><u>Pflicht Angabe</b></u></td>
                <td><b><u>Anzeigen im Frontent</b></u></td>
            </tr>
            
            <tr>
                <td><label><b>Kalender Anzeigen :</b></label></td>
                
                <td><input type="checkbox" value="ON" name="show_kalendar"' . $show_kalendar . '>Ja</td>

                <td><input type="checkbox" value="ON" name="no_kalendar"' . $no_kalendar . '>Nein</td>
            </tr>

            <tr>
                <td><label for="vorname"><b>Name / Vorname :</b></label></td>
                <td><input type="checkbox" id="vorname" value="ON" name="name"' . $name . '>
                <input type="hidden" name="bixxs_events_extra_tickets"></td>

                <td><input type="checkbox" value="ON" name="show_name"' . $show_name . '>
               </td>
            </tr>
            <tr>
                <td><label for="phone"><b>Telefonnummer :</b></label></td>
                <td><input type="checkbox" name="telephone" id="phone" value="ON"' . $telephone . '></td>
                <td><input type="checkbox" name="show_telephone"  value="ON"' . $show_telephone . '></td>
            </tr>
            <tr>
                <td><label for="<b>email"><b>E-Mail :</b></label></td>
                <td><input type="checkbox" name="email" id="email" value="ON"' . $email . '></td>
                <td><input type="checkbox" name="show_email" id="show_email" value="ON"' . $show_email . '></td>
            </tr>
            <tr>
                <td><label for="street"><b>Straße Hausnummer:</b></label></td>
                <td><input type="checkbox" name="street" id="street" value="ON"' . $street . '></td>
               
                <td><input type="checkbox" name="show_street" id="show_street" value="ON"' . $show_street . '></td>
            </tr>
            <tr>
                <td><label for="zipcode"><b>PLZ Ort:</b></label></td>
                <td><input type="checkbox" name="zipcity" id="zipcode" value="ON"' . $zipcity . '></td>
                
                <td><input type="checkbox" name="show_zipcity" id="zipcode" value="ON"' . $show_zipcity . '></td>
            </tr>

            <tr>
                <td>
                    <input type="submit" value="Absenden" class="button-primary">
                </td>
            </tr>
        </table>
    </form>
</div>';
}
