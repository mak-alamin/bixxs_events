<?php
add_action('admin_menu' , 'addingglobaleoptionenFunc');

function bixxs_events_addingbixxs_events_extraticketsFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("Globale Optionen" , BIXXS_EVENTS_TEXTDOMAIN) , __('Globale Optionen', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'bixxs-events-globaleoptionen' , 'bixxs_events_extraticketsFunc');
	}	
}		

function bixxs_events_extraticketsFunc(){

    // Get options
    $mlx_options = get_option('bixxs_events_options');
    if (isset($mlx_options['globale_optionen'])) {
        $mlx_guest_options = $mlx_options['globale_optionen'];
    }else{
        $mlx_guest_options = array(
            'option1' => true,
            'option2' => true,
            'option3' =>  true,
            'option4' => true,
            'option5' => true,
           );
    }

    if(isset($_POST['max_guests'])){

        // parse and sanitize user input
        $mlx_guest_options = array(
                'option1' => isset($_POST['option1']),
                'option2' => isset($_POST['option2']),
                'option3' =>  isset($_POST['option3']),
                'option4' => isset($_POST['option4']),
                'option5' => isset($_POST['option5']),
        );

        // save updated options
        $mlx_options['globale_optionen'] = $mlx_guest_options;
        update_option('bixxs_events_options', $mlx_options);

    }


    $option1 = isset($mlx_guest_options['option1']) && (bool)$mlx_guest_options['option1'] ? ' checked' : '';
    $option2 = isset($mlx_guest_options['option2']) && (bool)$mlx_guest_options['option2'] ? ' checked' : '';
    $option3 = isset($mlx_guest_options['option3']) && (bool)$mlx_guest_options['option3'] ? ' checked' : '';
    $option4 = isset($mlx_guest_options['option4']) && (bool)$mlx_guest_options['option4'] ? ' checked' : '';
    $option5 = isset($mlx_guest_options['option5']) && (bool)$mlx_guest_options['option5'] ? ' checked' : '';
    

echo '<div class="wrap">
    <h1>Globale Optionen</h1>
    <hr>

    <form action="" method="POST" name="save_extra_settings">

        <table cellspacing="10" cellpadding="5">
            <tr>
                <td><b>Optionen</b></td>
                <td><b>Globale Optionen</b></td>
            </tr>
            <tr>
                <td><label for="option1">Option 1 :</label></td>
                <td><input type="checkbox" id="option1" value="ON" name="option1"' . $option1 . '></td>
            </tr>
            <tr>
                <td><label for="option2">Option 2:</label></td>
                <td><input type="checkbox" name="option12" id="option2" value="ON"'. $option2 . '></td>
            </tr>
            <tr>
                <td><label for="option3">Option 3:</label></td>
                <td><input type="checkbox" name="option3" id="option3" value="ON"' . $option3 . '></td>
            </tr>
            <tr>
                <td><label for="option4">Option 4:</label></td>
                <td><input type="checkbox" name="option4" id="option4" value="ON"'. $option4 . '></td>
            </tr>
            <tr>
                <td><label for="zipcode">Option 5:</label></td>
                <td><input type="checkbox" name="option5" id="option5" value="ON"'. $option5 . '></td>
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
