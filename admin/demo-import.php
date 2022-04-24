<?php
add_action('admin_menu' , 'bixxs_events_addingbixxs_events_demoimportFunc');

function bixxs_events_addingbixxs_events_demoimportFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("demoimport" , BIXXS_EVENTS_TEXTDOMAIN) , __('Demoimport', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'bixxs-events-demoimport' , 'bixxs_events_demoimportFunc');
	}	
}		

function bixxs_events_demoimportFunc(){
	$einst_checked = 'checked';
	$tick_checked = 'checked';

	$einst_msg = '';
	$ticket_msg = '';

	if (isset($_POST['bixxs_events_import_demo'])) {
		global $wpdb;
		$einst_checked = '';
		$tick_checked = '';

		$import_einst = isset($_POST['import_einst_demo']) ? $_POST['import_einst_demo'] : 0;
		$import_tickets = isset($_POST['import_tickets_demo']) ? $_POST['import_tickets_demo'] : 0;

		if ( $import_einst ) {
			$einst_checked = 'checked';

            $ticketmaster_general_options = array(
                'logo' => plugin_dir_url(dirname(__FILE__)) . 'img/logo.png',
                'heading' => 'E&S Kassensysteme GmbH Marie-Curie-Strasse 6 D-47475 Kamp-Lintfort',
                'info' => 'Vielen Dank für Ihr vertrauen',
                'additional_info' => 'Zusatzinfos an den Kunden',
                'footer' => array(
                    1 => 'E&S Kassensysteme GmbH
Marie-Curie-Strasse 6
D-47475 Kamp-Lintfort
HRB 16659 Amtsgericht Kleve
UST:  119/5785/1237, Finanzamt Kamp-Lintfort',
                    2 => 'Tel: 02842-909100
Fax: 02842-
Email: info@pos-software.de
Website: https://pos-software.de',
                    3 => 'Bank: Deutsche Bank
Name: E S Kassensysteme
IBAN: DE 123345 3 456
BIC: xxx459852',
                ),
            );

            $ticketmaster_options = get_option('bixxs_events_options');
            $ticketmaster_options['general_settings'] = $ticketmaster_general_options;
            $saved = update_option('bixxs_events_options', $ticketmaster_options);


			if (! $saved) {
				echo '<div class="notice is-dismissible notice-warning"><p>Einstellungen Demo-Datenimport fehlgeschlagen.</p></div>';
			} else {
				echo '<div class="notice is-dismissible notice-success"><p>Einstellungen Demo-Daten erfolgreich importiert.</p></div>';
			}
		}

		if ( $import_tickets ) {
			$tick_checked = 'checked';
			$tickets_table = $wpdb->prefix . 'bixxs_events';
			
			$sql =	"INSERT INTO $tickets_table (`ticketnname`, `veranstalter`, `ort_veranstaltung`, `produktname_left`, `produktname_top`, `produktname_color`, `order_date_left`, `order_date_top`, `order_date_color`, `price_left`, `price_top`, `price_color`, `termien_left`, `termien_top`, `termien_color`, `veranstallter_left`, `veranstallter_top`, `veranstallter_color`, `veranstallter_ort_left`, `veranstallter_ort_top`, `veranstallter_ort_color`, `menge_left`, `menge_top`, `menge_color`, `qrcode_left`, `qrcode_top`, `qrcode_color`, `ticketimages`, `ticket_number_left`, `ticket_number_top`, `ticket_number_color`) VALUES
			('Laufzeiten 10.00 - 12.00 Uhr', 'Eissporthalle Troisdorf', '53844 Troisdorf', '50', '50', '#ffffff', '50', '80', '#ffffff', '50', '110', '#ffffff', '50', '140', '#ffffff', '50', '170', '#ffffff', '50', '200', '#ffffff', '50', '230', '#ffffff', '580', '240', '#fcff00', '/wp-content/plugins/bixxs_events/img/Ticket-1-Laufzeiten_10.00-12.00-Uhr.png', '50', '260', '#fcff00'),
			('Vip Platin', 'Eissporthalle Troisdorf', '53844 Troisdorf', '50', '50', '#ea6611', '50', '80', '#ea6611', '50', '110', '#ea6611', '50', '140', '#ea6611', '50', '170', '#ea6611', '50', '200', '#ea6611', '50', '230', '#ea6611', '1643', '300', 'ea6611', '/wp-content/plugins/bixxs_events/img/Vip-Platin.png', '450', '50', '#ea6611'),
			('Kutschfahrten', 'Bernsteinreiter', 'Treffpunkt ist der Servicepunkt', '50', '50', '#000000', '50', '70', '#000000', '50', '100', '#000000', ' 50', '130', '#000000', '50', ' 160', '#000000', '50', '190', '#000000', '50', '220', '#000000', '570', '250', '#000000', '/wp-content/plugins/bixxs_events/img/Ticket-Kutschfahrten.png', '50', '250', '#000000')";

			$inserted = $wpdb->query($sql);
					
			if (! $inserted) {
				echo '<div class="notice is-dismissible notice-warning"><p>Tickets demo data import failed.</p></div>';
			} else {
				echo '<div class="notice is-dismissible notice-success"><p>Tickets demo data imported successfully.</p></div>';
			}
		}

	}
?>
		<h1>Demo Daten importieren</h1>

		<form action="" method="post">
			<table>
				<tr>
						<td>
							<label for="import_einst_demo">
				<input type="checkbox" name="import_einst_demo" id="import_einst_demo" value="1" <?php echo $einst_checked; ?> >Demo PDF Einstellungen</label>		
						</td>
					</tr>
				<tr>
					<td>
				<label for="import_tickets_demo">
				<input type="checkbox" name="import_tickets_demo" id="import_tickets_demo" value="1" <?php echo $tick_checked; ?> >Demo Veranstaltungen</label>		
					</td>
				</tr>
			</table>
			<br>
			<input type="submit" name="bixxs_events_import_demo" id="" value="Import" class="button button-primary">
		</form>

	<?php
}

