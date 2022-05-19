<?php

/**
 * Tickets Einstellungen Class
 */

use Dompdf\Dompdf;
use Dompdf\Options;

class BixxsEventsEinstellungen
{
	public $notice;
	protected $set_ticketnname;
	protected $set_veranstalter;
	protected $set_ort_veranstaltung;
	protected $set_height;
	protected $set_width;
	protected $set_produktname_left;
	protected $set_produktname_top;
	protected $set_produktname_color;
	protected $set_order_date_left;
	protected $set_order_date_top;
	protected $set_order_date_color;
	protected $set_price_left;
	protected $set_price_top;
	protected $set_price_color;
	protected $set_termien_left;
	protected $set_termien_top;
	protected $set_termien_color;
	protected $set_veranstallter_left;
	protected $set_veranstallter_top;
	protected $set_veranstallter_color;
	protected $set_veranstallter_ort_left;
	protected $set_veranstallter_ort_top;
	protected $set_veranstallter_ort_color;
	protected $set_menge_left;
	protected $set_menge_top;
	protected $set_menge_color;
	protected $set_ticket_number_left;
	protected $set_ticket_number_top;
	protected $set_ticket_number_color;
	protected $set_qrcode_left;
	protected $set_qrcode_top;
	protected $set_qrcode_color;
	protected $set_ticketimage;

	protected $ticketmaster_general_options;

	public function __construct()
	{
		add_action('admin_menu', [$this, 'addingtickesbixxs_events_einstellungenFunc']);

		add_action('admin_head', [$this, 'ticketmasterDataticketsTableFunc']);

		add_action('admin_init', [$this, 'show_demo_pdf']);

		// set basic settings to general settings
		$ticketmaster_options = get_option('bixxs_events_options');
		if (isset($ticketmaster_options['general_settings'])) {
			$this->ticketmaster_general_options = $ticketmaster_options['general_settings'];
		} else {
			$this->ticketmaster_general_options = array(
				'logo' => (isset($_POST['logo'])) ?  esc_url_raw($_POST['logo']) : esc_url(wp_get_attachment_url(get_theme_mod('custom_logo'))),
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
	}

	/**
	 * Add Menu Page
	 */
	function addingtickesbixxs_events_einstellungenFunc()
	{
		$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
		if ($status) {
			add_submenu_page("bixxs-events", __("Veranstaltungseinstellungen", BIXXS_EVENTS_TEXTDOMAIN), __('Veranstaltungseinstellungen', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-tickeseinstellungen', [$this, 'ticketseinstellungenPageFunc']);
		}
	}

	/**
	 * Add Menu Page Content
	 */
	function ticketseinstellungenPageFunc()
	{
		if (isset($_GET['action']) && $_GET['action'] == 'bixxs_events_new_template') {
			require_once 'views/tickets-einstellungen/add_new_form.php';
		} elseif (isset($_GET['action']) && $_GET['action'] == 'edit-event') {
			require_once 'views/tickets-einstellungen/edit_ticket_form.php';
		} else if (isset($_POST['bixxs_events_update_template'])) {
			require_once 'views/tickets-einstellungen/edit_ticket_form.php';
		} else {
			require_once 'views/tickets-einstellungen/all_tickets.php';
		}
	}

	/**
	 * Insert, Update or Delete Data
	 */
	public function ticketmasterDataticketsTableFunc()
	{
		global $wpdb;
		$qryTicketmaster = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "bixxs_events(
			`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
			`ticketnname` varchar(100) NULL,
			`veranstalter` varchar(35) NULL,
			`ort_veranstaltung` varchar(50) NULL,
			`height` varchar(5) NULL,
			`width` varchar(5) NULL,
			`produktname_left` varchar(30) NULL,
			`produktname_top` varchar(30) NULL,
			`produktname_color` varchar(30) NULL,
			`order_date_left` varchar(10) NULL,
			`order_date_top` varchar(10) NULL,
			`order_date_color` varchar(10) NULL,
			`price_left` varchar(10) NULL,
			`price_top` varchar(10) NULL,
			`price_color` varchar(10) NULL,
			`termien_left` varchar(10) NULL,
			`termien_top` varchar(10) NULL,
			`termien_color` varchar(10) NULL,
			`veranstallter_left` varchar(10) NULL,
			`veranstallter_top` varchar(10) NULL,
			`veranstallter_color` varchar(10) NULL,
			`veranstallter_ort_left` varchar(10) NULL,
			`veranstallter_ort_top` varchar(10) NULL,
			`veranstallter_ort_color` varchar(10) NULL,
			`menge_left` varchar(10) NULL,
			`menge_top` varchar(10) NULL,
			`menge_color` varchar(10) NULL,
			`ticket_number_left` varchar(10) NULL,
			`ticket_number_top` varchar(10) NULL,
			`ticket_number_color` varchar(10) NULL,
			`qrcode_left` varchar(10) NULL,
			`qrcode_top` varchar(10) NULL,
			`qrcode_color` varchar(10) NULL,
			`ticketimages` varchar(250) NULL,
			
			PRIMARY KEY (`ID`)	
		);";

		$wpdb->query($qryTicketmaster);

		$table_name = $wpdb->prefix . 'bixxs_events';


		/**
		 * Insert new Ticket Template
		 */
		if (isset($_POST['bixxs_events_save_template'])) {
			$DBP_ticketnname = (isset($_POST['ticketnname'])) ? $_POST['ticketnname'] : '';
			$DBP_veranstalter = (isset($_POST['veranstalter'])) ? $_POST['veranstalter'] : '';
			$DBP_ort_veranstaltung = (isset($_POST['ort_veranstaltung'])) ? $_POST['ort_veranstaltung'] : '';
			$DBP_height = (isset($_POST['height'])) ? $_POST['height'] : '';
			$DBP_width = (isset($_POST['width'])) ? $_POST['width'] : '';
			$DBP_produktname_left = (isset($_POST['produktname_left'])) ? $_POST['produktname_left'] : '';
			$DBP_produktname_top = (isset($_POST['produktname_top'])) ? $_POST['produktname_top'] : '';
			$DBP_produktname_color = (isset($_POST['produktname_color'])) ? $_POST['produktname_color'] : '';
			$DBP_order_date_left = (isset($_POST['order_date_left'])) ? $_POST['order_date_left'] : '';
			$DBP_order_date_top = (isset($_POST['order_date_top'])) ? $_POST['order_date_top'] : '';
			$DBP_order_date_color = (isset($_POST['order_date_color'])) ? $_POST['order_date_color'] : '';
			$DBP_price_left = (isset($_POST['price_left'])) ? $_POST['price_left'] : '';
			$DBP_price_top = (isset($_POST['price_top'])) ? $_POST['price_top'] : '';
			$DBP_price_color = (isset($_POST['price_color'])) ? $_POST['price_color'] : '';
			$DBP_termien_left = (isset($_POST['termien_left'])) ? $_POST['termien_left'] : '';
			$DBP_termien_top = (isset($_POST['termien_top'])) ? $_POST['termien_top'] : '';
			$DBP_termien_color = (isset($_POST['termien_color'])) ? $_POST['termien_color'] : '';
			$DBP_veranstallter_left = (isset($_POST['veranstallter_left'])) ? $_POST['veranstallter_left'] : '';
			$DBP_veranstallter_top = (isset($_POST['veranstallter_top'])) ? $_POST['veranstallter_top'] : '';
			$DBP_veranstallter_color = (isset($_POST['veranstallter_color'])) ? $_POST['veranstallter_color'] : '';
			$DBP_veranstallter_ort_left = (isset($_POST['veranstallter_ort_left'])) ? $_POST['veranstallter_ort_left'] : '';
			$DBP_veranstallter_ort_top = (isset($_POST['veranstallter_ort_top'])) ? $_POST['veranstallter_ort_top'] : '';
			$DBP_veranstallter_ort_color = (isset($_POST['veranstallter_ort_color'])) ? $_POST['veranstallter_ort_color'] : '';
			$DBP_menge_left = (isset($_POST['menge_left'])) ? $_POST['menge_left'] : '';
			$DBP_menge_top = (isset($_POST['menge_top'])) ? $_POST['menge_top'] : '';
			$DBP_menge_color = (isset($_POST['menge_color'])) ? $_POST['menge_color'] : '';
			$DBP_ticket_number_left = (isset($_POST['ticket_number_left'])) ? $_POST['ticket_number_left'] : '';
			$DBP_ticket_number_top = (isset($_POST['ticket_number_top'])) ? $_POST['ticket_number_top'] : '';
			$DBP_ticket_number_color = (isset($_POST['ticket_number_color'])) ? $_POST['ticket_number_color'] : '';
			$DBP_qrcode_left = (isset($_POST['qrcode_left'])) ? $_POST['qrcode_left'] : '';
			$DBP_qrcode_top = (isset($_POST['qrcode_top'])) ? $_POST['qrcode_top'] : '';
			$DBP_qrcode_color = (isset($_POST['qrcode_color'])) ? $_POST['qrcode_color'] : '';
			$DBP_ticketimage = (isset($_POST['ticketimage'])) ? $_POST['ticketimage'] : '';

			$sql = "INSERT INTO $table_name
			(`ticketnname`,`veranstalter`,`ort_veranstaltung`,`height`,`width`,`produktname_left`,`produktname_top`,`produktname_color`,`order_date_left`, `order_date_top`, `order_date_color`, `price_left`, `price_top`, `price_color`,  
			`termien_left`, `termien_top`, `termien_color`, `veranstallter_left`, `veranstallter_top`, `veranstallter_color`, `veranstallter_ort_left`, `veranstallter_ort_top`, `veranstallter_ort_color`, `menge_left`, `menge_top`,`menge_color`, `ticket_number_left`, `ticket_number_top`,`ticket_number_color` , `qrcode_left`, `qrcode_top`, `qrcode_color`, `ticketimages` ) 
			values ('$DBP_ticketnname','$DBP_veranstalter', '$DBP_ort_veranstaltung', '$DBP_height', '$DBP_width', '$DBP_produktname_left', '$DBP_produktname_top', '$DBP_produktname_color', '$DBP_order_date_left', '$DBP_order_date_top',' $DBP_order_date_color', '$DBP_price_left', '$DBP_price_top', '$DBP_price_color',' $DBP_termien_left', ' $DBP_termien_top', '$DBP_termien_color','$DBP_veranstallter_left',' $DBP_veranstallter_top', '$DBP_veranstallter_color', '$DBP_veranstallter_ort_left', '$DBP_veranstallter_ort_top', '$DBP_veranstallter_ort_color', '$DBP_menge_left','$DBP_menge_top', '$DBP_menge_color', '$DBP_ticket_number_left' , '$DBP_ticket_number_top' , '$DBP_ticket_number_color' , '$DBP_qrcode_left', '$DBP_qrcode_top', '$DBP_qrcode_color', '$DBP_ticketimage')";

			$inserted = $wpdb->query($sql);

			if ($inserted) {
				$this->notice = "Ticket erfolgreich hinzugef&#252;gt.";
			}
		}


		/**
		 * Update Ticket Template
		 */

		if (isset($_GET['action']) && $_GET['action'] == 'edit-event') {

			$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

			$this->mlx_show_updated_field_values($table_name, $id);
		}

		if (isset($_POST['bixxs_events_update_template'])) {
			$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

			$DBP_ticketnname = (isset($_POST['ticketnname'])) ? $_POST['ticketnname'] : '';
			$DBP_veranstalter = (isset($_POST['veranstalter'])) ? $_POST['veranstalter'] : '';
			$DBP_ort_veranstaltung = (isset($_POST['ort_veranstaltung'])) ? $_POST['ort_veranstaltung'] : '';
			$DBP_height = (isset($_POST['height'])) ? $_POST['height'] : '';
			$DBP_width = (isset($_POST['width'])) ? $_POST['width'] : '';
			$DBP_produktname_left = (isset($_POST['produktname_left'])) ? $_POST['produktname_left'] : '';
			$DBP_produktname_top = (isset($_POST['produktname_top'])) ? $_POST['produktname_top'] : '';
			$DBP_produktname_color = (isset($_POST['produktname_color'])) ? $_POST['produktname_color'] : '';
			$DBP_order_date_left = (isset($_POST['order_date_left'])) ? $_POST['order_date_left'] : '';
			$DBP_order_date_top = (isset($_POST['order_date_top'])) ? $_POST['order_date_top'] : '';
			$DBP_order_date_color = (isset($_POST['order_date_color'])) ? $_POST['order_date_color'] : '';
			$DBP_price_left = (isset($_POST['price_left'])) ? $_POST['price_left'] : '';
			$DBP_price_top = (isset($_POST['price_top'])) ? $_POST['price_top'] : '';
			$DBP_price_color = (isset($_POST['price_color'])) ? $_POST['price_color'] : '';
			$DBP_termien_left = (isset($_POST['termien_left'])) ? $_POST['termien_left'] : '';
			$DBP_termien_top = (isset($_POST['termien_top'])) ? $_POST['termien_top'] : '';
			$DBP_termien_color = (isset($_POST['termien_color'])) ? $_POST['termien_color'] : '';
			$DBP_veranstallter_left = (isset($_POST['veranstallter_left'])) ? $_POST['veranstallter_left'] : '';
			$DBP_veranstallter_top = (isset($_POST['veranstallter_top'])) ? $_POST['veranstallter_top'] : '';
			$DBP_veranstallter_color = (isset($_POST['veranstallter_color'])) ? $_POST['veranstallter_color'] : '';
			$DBP_veranstallter_ort_left = (isset($_POST['veranstallter_ort_left'])) ? $_POST['veranstallter_ort_left'] : '';
			$DBP_veranstallter_ort_top = (isset($_POST['veranstallter_ort_top'])) ? $_POST['veranstallter_ort_top'] : '';
			$DBP_veranstallter_ort_color = (isset($_POST['veranstallter_ort_color'])) ? $_POST['veranstallter_ort_color'] : '';
			$DBP_menge_left = (isset($_POST['menge_left'])) ? $_POST['menge_left'] : '';
			$DBP_menge_top = (isset($_POST['menge_top'])) ? $_POST['menge_top'] : '';
			$DBP_menge_color = (isset($_POST['menge_color'])) ? $_POST['menge_color'] : '';
			$DBP_ticket_number_left = (isset($_POST['ticket_number_left'])) ? $_POST['ticket_number_left'] : '';
			$DBP_ticket_number_top = (isset($_POST['ticket_number_top'])) ? $_POST['ticket_number_top'] : '';
			$DBP_ticket_number_color = (isset($_POST['ticket_number_color'])) ? $_POST['ticket_number_color'] : '';
			$DBP_qrcode_left = (isset($_POST['qrcode_left'])) ? $_POST['qrcode_left'] : '';
			$DBP_qrcode_top = (isset($_POST['qrcode_top'])) ? $_POST['qrcode_top'] : '';
			$DBP_qrcode_color = (isset($_POST['qrcode_color'])) ? $_POST['qrcode_color'] : '';
			$DBP_ticketimage = (isset($_POST['ticketimage'])) ? $_POST['ticketimage'] : '';

			$updated = $wpdb->query($wpdb->prepare("UPDATE $table_name SET 
				ticketnname = '%s',
				veranstalter='%s',
				ort_veranstaltung = '%s',
				height = '%s',
				width = '%s',
				produktname_left = '%s',
				produktname_top = '%s',
				produktname_color = '%s',
				order_date_left = '%s',
				order_date_top = '%s',
				order_date_color = '%s',
				price_left = '%s',
				price_top = '%s',
				price_color = '%s',
				termien_left = '%s',
				termien_top = '%s',
				termien_color = '%s',
				veranstallter_left = '%s',
				veranstallter_top = '%s',
				veranstallter_color = '%s',
				veranstallter_ort_left = '%s',
				veranstallter_ort_top = '%s',
				veranstallter_ort_color = '%s',
				menge_left = '%s',
				menge_top = '%s',
				menge_color = '%s',
				ticket_number_left = '%s',
				ticket_number_top = '%s',
				ticket_number_color = '%s',
				qrcode_left = '%s',
				qrcode_top = '%s',
				qrcode_color = '%s',
				ticketimages = '%s' 
				WHERE ID=%d", $DBP_ticketnname, $DBP_veranstalter, $DBP_ort_veranstaltung, $DBP_height, $DBP_width, $DBP_produktname_left, $DBP_produktname_top, $DBP_produktname_color, $DBP_order_date_left, $DBP_order_date_top, $DBP_order_date_color, $DBP_price_left,  $DBP_price_top, $DBP_price_color, $DBP_termien_left, $DBP_termien_top, $DBP_termien_color, $DBP_veranstallter_left, $DBP_veranstallter_top,  $DBP_veranstallter_color, $DBP_veranstallter_ort_left, $DBP_veranstallter_ort_top, $DBP_veranstallter_ort_color, $DBP_menge_left, $DBP_menge_top, $DBP_menge_color, $DBP_ticket_number_left, $DBP_ticket_number_top, $DBP_ticket_number_color, $DBP_qrcode_left, $DBP_qrcode_top, $DBP_qrcode_color, $DBP_ticketimage, $id));

			if ($updated) {
				$this->notice = "Ticket update erfolgreich.";

				$this->mlx_show_updated_field_values($table_name, $id);
			}
		}


		/**
		 * Delete a Ticket Template
		 */
		if (isset($_GET['action']) && $_GET['action'] == 'delete-event') {

			$id = (isset($_GET['id'])) ? $_GET['id'] : 0;

			$deleted = $wpdb->delete($table_name, array('id' => $id));

			if ($deleted) {
				$this->notice = "Ticket wurde erfolgreich gel&#246;scht.";
			}
		}
	}

	/**
	 * Show Updated Field Values
	 */
	protected function mlx_show_updated_field_values($table_name, $id)
	{
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM $table_name WHERE ID=$id");

		foreach ($result as $print) {

			$this->set_ticketnname = $print->ticketnname;
			$this->set_veranstalter = $print->veranstalter;
			$this->set_ort_veranstaltung = $print->ort_veranstaltung;
			$this->set_height = $print->height;
			$this->set_width = $print->width;
			$this->set_produktname_left = $print->produktname_left;
			$this->set_produktname_top = $print->produktname_top;
			$this->set_produktname_color = $print->produktname_color;
			$this->set_order_date_left = $print->order_date_left;
			$this->set_order_date_top = $print->order_date_top;
			$this->set_order_date_color = $print->order_date_color;
			$this->set_price_left = $print->price_left;
			$this->set_price_top = $print->price_top;
			$this->set_price_color = $print->price_color;
			$this->set_termien_left = $print->termien_left;
			$this->set_termien_top = $print->termien_top;
			$this->set_termien_color = $print->termien_color;
			$this->set_veranstallter_left = $print->veranstallter_left;
			$this->set_veranstallter_top = $print->veranstallter_top;
			$this->set_veranstallter_color = $print->veranstallter_color;
			$this->set_veranstallter_ort_left = $print->veranstallter_ort_left;
			$this->set_veranstallter_ort_top = $print->veranstallter_ort_top;
			$this->set_veranstallter_ort_color = $print->veranstallter_ort_color;
			$this->set_menge_left = $print->menge_left;
			$this->set_menge_top = $print->menge_top;
			$this->set_menge_color = $print->menge_color;
			$this->set_ticket_number_left = $print->ticket_number_left;
			$this->set_ticket_number_top = $print->ticket_number_top;
			$this->set_ticket_number_color = $print->ticket_number_color;
			$this->set_qrcode_left = $print->qrcode_left;
			$this->set_qrcode_top = $print->qrcode_top;
			$this->set_qrcode_color = $print->qrcode_color;
			$this->set_ticketimage = $print->ticketimages;
		}
	}

	/**
	 * Show Demo PDF Template
	 */
	public function show_demo_pdf()
	{
		if (isset($_POST['bixxs_events_show_demo_pdf_template'])) {
			$ticketnname = (isset($_POST['ticketnname'])) ? $_POST['ticketnname'] : '';
			$veranstalter = (isset($_POST['veranstalter'])) ? $_POST['veranstalter'] : '';
			$ort_veranstaltung = (isset($_POST['ort_veranstaltung'])) ? $_POST['ort_veranstaltung'] : '';
			$height = (isset($_POST['height'])) ? $_POST['height'] : '';
			$width = (isset($_POST['width'])) ? $_POST['width'] : '';
			$produktname_left = (isset($_POST['produktname_left'])) ? $_POST['produktname_left'] : '';
			$produktname_top = (isset($_POST['produktname_top'])) ? $_POST['produktname_top'] : '';
			$produktname_color = (isset($_POST['produktname_color'])) ? $_POST['produktname_color'] : '';
			$order_date_left = (isset($_POST['order_date_left'])) ? $_POST['order_date_left'] : '';
			$order_date_top = (isset($_POST['order_date_top'])) ? $_POST['order_date_top'] : '';
			$order_date_color = (isset($_POST['order_date_color'])) ? $_POST['order_date_color'] : '';
			$price_left = (isset($_POST['price_left'])) ? $_POST['price_left'] : '';
			$price_top = (isset($_POST['price_top'])) ? $_POST['price_top'] : '';
			$price_color = (isset($_POST['price_color'])) ? $_POST['price_color'] : '';
			$termien_left = (isset($_POST['termien_left'])) ? $_POST['termien_left'] : '';
			$termien_top = (isset($_POST['termien_top'])) ? $_POST['termien_top'] : '';
			$termien_color = (isset($_POST['termien_color'])) ? $_POST['termien_color'] : '';
			$veranstallter_left = (isset($_POST['veranstallter_left'])) ? $_POST['veranstallter_left'] : '';
			$veranstallter_top = (isset($_POST['veranstallter_top'])) ? $_POST['veranstallter_top'] : '';
			$veranstallter_color = (isset($_POST['veranstallter_color'])) ? $_POST['veranstallter_color'] : '';
			$veranstallter_ort_left = (isset($_POST['veranstallter_ort_left'])) ? $_POST['veranstallter_ort_left'] : '';
			$veranstallter_ort_top = (isset($_POST['veranstallter_ort_top'])) ? $_POST['veranstallter_ort_top'] : '';
			$veranstallter_ort_color = (isset($_POST['veranstallter_ort_color'])) ? $_POST['veranstallter_ort_color'] : '';
			$menge_left = (isset($_POST['menge_left'])) ? $_POST['menge_left'] : '';
			$menge_top = (isset($_POST['menge_top'])) ? $_POST['menge_top'] : '';
			$menge_color = (isset($_POST['menge_color'])) ? $_POST['menge_color'] : '';
			$ticket_number_left = (isset($_POST['ticket_number_left'])) ? $_POST['ticket_number_left'] : '';
			$ticket_number_top = (isset($_POST['ticket_number_top'])) ? $_POST['ticket_number_top'] : '';
			$ticket_number_color = (isset($_POST['ticket_number_color'])) ? $_POST['ticket_number_color'] : '';
			$qrcode_left = (isset($_POST['qrcode_left'])) ? $_POST['qrcode_left'] : '';
			$qrcode_top = (isset($_POST['qrcode_top'])) ? $_POST['qrcode_top'] : '';
			$qrcode_color = (isset($_POST['qrcode_color'])) ? $_POST['qrcode_color'] : '';
			$ticketimage = (isset($_POST['ticketimage'])) ? $_POST['ticketimage'] : '';

			ob_start();
			$html = "";
			require_once __DIR__ .  '/views/tickets-einstellungen/demo_pdf_template.php';

			$html = ob_get_clean();

			$options = new Options();
			$options->set('defaultFont', 'DejaVu Sans');

			$dompdf = new Dompdf($options);

			$dompdf->setPaper('A4');

			$dompdf->loadHtml($html);

			// Render the HTML as PDF
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream('pdf_template_demo.pdf', array('Attachment' => false));
			die();
		}
	}
}

new BixxsEventsEinstellungen();
