<?php

/**
 * PDF Template Class
 */

use Dompdf\Dompdf;
use Dompdf\Options;

class Bixxs_Events_Briefkopf
{
	protected $ticketmaster_options;
	protected $ticketmaster_general_options;

	public function __construct()
	{
		// set basic settings to general settings
		$this->ticketmaster_options = get_option('bixxs_events_options');

		if (isset($ticketmaster_options['general_settings'])) {
			$this->ticketmaster_general_options = $ticketmaster_options['general_settings'];
		} else {
			$this->ticketmaster_general_options = array(
				'logo' => (isset($_POST['logo'])) ? esc_url_raw($_POST['logo']) : esc_url(wp_get_attachment_url(get_theme_mod('custom_logo'))),
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

		add_action('admin_menu', [$this, 'bixxs_events_addingbixxs_events_einstellungenFunc']);

		add_action('init', [$this, 'rebooking_tickets']);
		add_action('init', [$this, 'mlx_generate_events_pdf_template']);
		add_action('init', [$this, 'show_demo_pdf_template_at_admin']);

		add_filter('woocommerce_thankyou_order_received_text', [$this, 'woo_change_order_received_text'], 10, 3);

		add_action('woocommerce_order_details_before_order_table', [$this, 'woo_add_pdf_button_in_order_details'],);

		add_action('admin_enqueue_scripts', [$this, 'svg_to_png_script']);
		add_action('wp_enqueue_scripts', [$this, 'svg_to_png_script']);

		add_action('init', [$this, 'generate_pdf_from_email']);
	}

	/**
	 * Add Menu Page
	 */
	function bixxs_events_addingbixxs_events_einstellungenFunc()
	{
		$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
		if ($status) {
			add_submenu_page("bixxs-events", __("Einstellungen", BIXXS_EVENTS_TEXTDOMAIN), __('Einstellungen', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-einstellungen', [$this, 'settings_page_function']);
		}
	}

	/**
	 * Add Menu Page Content
	 */
	function settings_page_function()
	{
		//save options
		if (isset($_POST['save_general_settings'])) {

			$this->ticketmaster_general_options = array(
				'logo' => (isset($_POST['logo'])) ?  esc_url_raw($_POST['logo']) : esc_url(wp_get_attachment_url(get_theme_mod('custom_logo'))),
				'heading' => sanitize_text_field($_POST['heading']),
				'info' => sanitize_textarea_field($_POST['info']),
				'additional_info' => sanitize_textarea_field($_POST['additional_info']),
				'footer' => array(
					1 => sanitize_textarea_field($_POST['footer'][1]),
					2 => sanitize_textarea_field($_POST['footer'][2]),
					3 => sanitize_textarea_field($_POST['footer'][3]),
				),
			);

			$ticketmaster_options = get_option('bixxs_events_options');
			$ticketmaster_options['general_settings'] = $this->ticketmaster_general_options;
			update_option('bixxs_events_options', $ticketmaster_options);
		}

		// Render settings page
		if (file_exists(__DIR__ . '/views/briefkopf/html_form.php')) {
			require_once __DIR__ . '/views/briefkopf/html_form.php';
		}

		ticketmaster_render_general_settings($this->ticketmaster_options['general_settings']);
	}

	/*
	* Insert Data in Database
	*/
	function rebooking_tickets()
	{
		if (isset($_POST['rebook_ticket'])) {
			$item = new WC_Order_Item_Product(sanitize_text_field($_POST['item_id']));
			$date = sanitize_text_field($_POST['bixxs_events_reserve_time']);
			$booked_date = wc_get_order_item_meta($item->get_id(), 'Reservierung Datum', true);


			if ($date != $booked_date) {

				$now = time();
				$time_date = strtotime($date);
				$time_booked = strtotime($booked_date);

				if ($time_date < $now) {
					wc_add_notice('Der Tag muss in der Zukunft liegen.', 'error');
				} else if ($time_booked < $now) {
					wc_add_notice('Das Ticket liegt in der Vergangenheit und kann nicht umgebucht werden.', 'error');
				} else {

					setlocale(LC_TIME, 'de_DE', 'deu_deu');

					$day_of_week = strtolower(explode(',', strftime('%A, %d.%m.%Y', strtotime($date)))[0]);

					$tickets_arr = unserialize($item->get_product()->get_meta('timeslots_selection'))['tickets'];

					$available_tickets = array_sum($tickets_arr[$day_of_week]);

					$guests = count(json_decode(wc_get_order_item_meta($item->get_id(), '_mlx_guests', true), true));

					$booked = count(bixxs_events_get_guests($date, $item->get_product_id()));

					if (($available_tickets - $booked) >= $guests) {
						$item->update_meta_data('Reservierung Datum', $date);
						$item->save();

						$order = $item->get_order();
						$order_dates = $order->get_meta('Reservierung Datum');
						$order->update_meta_data('Reservierung Datum', $order_dates . ',' . $date);
						$order->save();

						bixxs_events_send_email('rebook_ticket', $item);

						wc_add_notice('Das Ticket wurde erfolgreich umgebucht');
					} else {
						wc_add_notice('An dem gewählten Tag sind leider nicht genügend Tickets verfügbar.', 'error');
					}
				}
			} else {
				wc_add_notice('Ihre Tickets sind bereits für das ausgewählte Datum', 'error');
			}
		}
	}

	public function svg_to_png_script()
	{
		wp_enqueue_script('svg_to_png', plugin_dir_url(__FILE__) . '/phpqrcode-master/svgtopng.js', array('jquery'), null, false);
		wp_enqueue_script('ticketmaster-change-date', plugin_dir_url(__FILE__) . '/js/rebookticket.js', array('jquery'), null, false);
	}


	/**
	 * Generate a demo PDF Template at admin
	 */
	public function show_demo_pdf_template_at_admin()
	{
		if (isset($_POST['print_pdf_template'])) {

			$options = new Options();
			$options->set('defaultFont', 'DejaVu Sans');

			$dompdf = new Dompdf($options);

			$dompdf->setPaper('A4');

			$html = '';
			ob_start();
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="Tickets.pdf"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');

			require_once 'views/briefkopf/demo_pdf_template_html.php';

			ticketmaster_render_demo_template($this->ticketmaster_options['general_settings']);

			$html = ob_get_clean();

			$dompdf->loadHtml($html);

			// Render the HTML as PDF
			$dompdf->render();

			// Output the generated PDF to Browser
			$dompdf->stream("Ticket.pdf", array('Attachment' => false));
			exit;
		}
	}

	/**
	 * Generate PDF from email link
	 */
	public function generate_pdf_from_email()
	{
		if (!isset($_GET['action']) || $_GET['action'] != 'pdf_download' || !isset($_GET['download_token'])) {
			return;
		}

		$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

		$download_token = get_post_meta($order_id, 'pdf_download_token', true);

		if (urldecode(trim($download_token)) != urldecode(trim($_GET['download_token']))) {
			wp_die("You are not allowed to access here! Invalid Token.");
		}

		$item_id_from_email = isset($_GET['item_id']) ? $_GET['item_id'] : 0;

		$ticket_id = isset($_GET['ticket_number']) ? $_GET['ticket_number'] : 0;

		global $wpdb;

		$order = wc_get_order($order_id);

		$shipping_method = $order->get_shipping_method();

		$item = $order->get_items()[$item_id_from_email];

		$product = $item->get_product();

		$type = $item->get_type();
		$product_id = $item->get_product_id();

		$order_date = $order->get_date_created('Y/m/d')->format('d.m.Y');

		$billfirstname = $order->get_billing_first_name();
		$bill_lastname = $order->get_billing_last_name();
		$bill_l1 = $order->get_billing_address_1();
		$bill_l2 = $order->get_billing_address_2();
		$bill_postcode = $order->get_billing_postcode();
		$bill_com = $order->get_billing_company();
		$bill_city = $order->get_billing_city();

		$ticket_name = $item->get_name();
		$ticket_price = get_post_meta($product_id, '_regular_price', true);

		$quantity = $item->get_quantity();
		$bixxs_events_reserve_time = $item->get_meta('Reservierung Datum');

		$ticket_template_id = get_post_meta($product_id, 'bixxs_events_event_template', true);

		$product_type = ($ticket_template_id) ? "Ticket" : "Gutschein";

		if ($ticket_template_id) {
			$tick_result = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}bixxs_events WHERE id=%d",
				$ticket_template_id
			));

			$veranstalter = trim($tick_result[0]->veranstalter);
			$ort_veranstaltung = trim($tick_result[0]->ort_veranstaltung);
			$termien_left = trim($tick_result[0]->termien_left);
			$termien_top = trim($tick_result[0]->termien_top);
			$termien_color = trim($tick_result[0]->termien_color);
			$veranstallter_left = trim($tick_result[0]->veranstallter_left);
			$veranstallter_top = trim($tick_result[0]->veranstallter_top);
			$veranstallter_color = trim($tick_result[0]->veranstallter_color);
			$veranstallter_ort_left = trim($tick_result[0]->veranstallter_ort_left);
			$veranstallter_ort_top = trim($tick_result[0]->veranstallter_ort_top);
			$veranstallter_ort_color = trim($tick_result[0]->veranstallter_ort_color);
		}

		$ticket_img = trim($tick_result[0]->ticketimages);

		$img_height = trim($tick_result[0]->height);
		$img_width = trim($tick_result[0]->width);

		$produktname_left = trim($tick_result[0]->produktname_left);
		$produktname_top = trim($tick_result[0]->produktname_top);
		$productname_color = trim($tick_result[0]->produktname_color);
		$order_date_left = trim($tick_result[0]->order_date_left);
		$order_date_top = trim($tick_result[0]->order_date_top);
		$order_date_color = trim($tick_result[0]->order_date_color);
		$price_left = trim($tick_result[0]->price_left);
		$price_top = trim($tick_result[0]->price_top);
		$price_color = trim($tick_result[0]->price_color);
		$menge_left = trim($tick_result[0]->menge_left);
		$menge_top = trim($tick_result[0]->menge_top);
		$menge_color = trim($tick_result[0]->menge_color);
		$ticket_number_left = trim($tick_result[0]->ticket_number_left);
		$ticket_number_top = trim($tick_result[0]->ticket_number_top);
		$ticket_number_color = trim($tick_result[0]->ticket_number_color);
		$qrcode_left = trim($tick_result[0]->qrcode_left);
		$qrcode_top = trim($tick_result[0]->qrcode_top);
		$qrcode_color = trim($tick_result[0]->qrcode_color);

		$guests = json_decode($item->get_meta('_mlx_guests'), true);
		$guest_number = isset($_GET['guest_number']) ? $_GET['guest_number'] : 1;

		$qr_code_img = $this->generate_qr_code_url($item_id_from_email, $guests, $guest_number, $ticket_template_id, $ticket_name, $order_date, $ticket_price, $veranstalter, $ort_veranstaltung, $qrcode_color);

		$customer_note = $order->get_customer_note();

		//////////////////////////////
		$paymethod = $order->get_payment_method();
		$paymethod_title = $order->get_payment_method_title();

		// Addons
		$bixxs_events_addons = $this->generate_addons($item);

		$pdf_name = "Ticket-$ticket_id";

		$html = '';

		ob_start();

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="Ticket.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');

		if (file_exists(plugin_dir_path(__FILE__) . 'views/email_settings/email_pdf.php')) {
			require_once plugin_dir_path(__FILE__) . 'views/email_settings/email_pdf.php';
		}

		$html = ob_get_clean();

		$options = new Options();
		$options->set('defaultFont', 'DejaVu Sans');

		$dompdf = new Dompdf($options);
		$dompdf->setPaper('A4');

		$dompdf->loadHtml($html);

		// Render the HTML as PDF
		$dompdf->render();

		ob_end_clean();
		// Output the generated PDF to Browser
		$dompdf->stream($pdf_name . '.pdf', array('Attachment' => false));
		exit;
	}

	/**
	 * Get QR Code Url
	 */
	public function generate_qr_code_url($item_id, $guests = [], $guest_number, $ticket_template_id, $ticket_name, $order_date, $ticket_price, $veranstalter, $ort_veranstaltung, $qrcode_color)
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

		$image_url = BIXXS_EVENTS_PLUGIN_URL . substr($image_file, strpos($image_file, 'admin/phpqrcode-master/temp'));

		return $image_url;
	}

	/**
	 * Generate Addons
	 */
	public function generate_addons($item)
	{
		$addons = '';

		$addon_fields = json_decode($item->get_meta('_bixxs_events_fields'), true);
		$selected_addons = json_decode($item->get_meta('_bixxs_events_addons'), true);

		if (!$selected_addons) {
			$selected_addons = array();
		}

		foreach ($selected_addons as $key => $selected_addon) {
			$addon_field = $addon_fields[$key];
			if ($selected_addon == '' || ($selected_addon == 0 && $addon_field['selection'] == 'number'))
				continue;

			if ($addon_field['selection'] == 'mc') {
				$value = implode(' ,', $selected_addon);
			} else if ($addon_field['selection'] == 'dd') {
				$value = $addon_field['options'][$selected_addon]['text'];
			} else {
				$value = $selected_addon;
			}

			$addons .= $addon_fields[$key]['label'] . ': ' . $value . '<br>';
		}

		return $addons;
	}

	/**
	 * Generate the "PDF Print Button" and 
	 * Change Order received texts after placing an order
	 */
	function woo_change_order_received_text($var, $order)
	{
		global $wpdb;

		$chosen_shipping_method = $order->get_shipping_method();

		foreach ($order->get_items() as $item_id => $item) {
			$product = $item->get_product();

			if ('bixxs_events_product' != $product->get_type()) {
				continue;
			}

			$allmeta = $item->get_meta_data();
			$somemeta = $item->get_meta('whatever', true);

			$type = $item->get_type();
			$product_id = $item->get_product_id();

			$order_date = $order->get_date_created('Y/m/d')->format('d.m.Y');
			$bill_postcode = $order->get_billing_postcode();
			$bill_com = $order->get_billing_company();

			$ticket_name = $item->get_name();
			$ticket_price = get_post_meta($product_id, '_regular_price', true);

			$quantity = $item->get_quantity();
			$bixxs_events_reserve_time = $item->get_meta('Reservierung Datum');

			$ticket_template_id = get_post_meta($product_id, 'bixxs_events_event_template', true);

			$product_type = ($ticket_template_id) ? "Ticket" : "Gutschein";

			if ($ticket_template_id) {
				$tick_result = $wpdb->get_results($wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}bixxs_events WHERE id=%d",
					$ticket_template_id
				));

				$veranstalter = trim($tick_result[0]->veranstalter);
				$ort_veranstaltung = trim($tick_result[0]->ort_veranstaltung);
				$termien_left = trim($tick_result[0]->termien_left);
				$termien_top = trim($tick_result[0]->termien_top);
				$termien_color = trim($tick_result[0]->termien_color);
				$veranstallter_left = trim($tick_result[0]->veranstallter_left);
				$veranstallter_top = trim($tick_result[0]->veranstallter_top);
				$veranstallter_color = trim($tick_result[0]->veranstallter_color);
				$veranstallter_ort_left = trim($tick_result[0]->veranstallter_ort_left);
				$veranstallter_ort_top = trim($tick_result[0]->veranstallter_ort_top);
				$veranstallter_ort_color = trim($tick_result[0]->veranstallter_ort_color);
			}

			$ticket_img = trim($tick_result[0]->ticketimages);

			$img_height = trim($tick_result[0]->height);
			$img_width = trim($tick_result[0]->width);

			$produktname_left = trim($tick_result[0]->produktname_left);
			$produktname_top = trim($tick_result[0]->produktname_top);
			$productname_color = trim($tick_result[0]->produktname_color);
			$order_date_left = trim($tick_result[0]->order_date_left);
			$order_date_top = trim($tick_result[0]->order_date_top);
			$order_date_color = trim($tick_result[0]->order_date_color);
			$price_left = trim($tick_result[0]->price_left);
			$price_top = trim($tick_result[0]->price_top);
			$price_color = trim($tick_result[0]->price_color);
			$menge_left = trim($tick_result[0]->menge_left);
			$menge_top = trim($tick_result[0]->menge_top);
			$menge_color = trim($tick_result[0]->menge_color);
			$ticket_number_left = trim($tick_result[0]->ticket_number_left);
			$ticket_number_top = trim($tick_result[0]->ticket_number_top);
			$ticket_number_color = trim($tick_result[0]->ticket_number_color);
			$qrcode_left = trim($tick_result[0]->qrcode_left);
			$qrcode_top = trim($tick_result[0]->qrcode_top);
			$qrcode_color = trim($tick_result[0]->qrcode_color);


			$customer_note = $order->get_customer_note();


			//////////////////////////////
			$paymethod = $order->get_payment_method();
			$paymethod_title = $order->get_payment_method_title();

			if ($order->get_status() == 'completed' || ($paymethod != 'bacs' && $paymethod != 'cheque' && $paymethod != 'cod')) {
				$guests = json_decode($item->get_meta('_mlx_guests'), true);

				echo '<h3>' . $ticket_name . ' am ' . $bixxs_events_reserve_time . '</h3>';
				echo "<h4> Klicken Sie auf den Button unten, um Ihre Bestellung zu drucken $product_type</h4>";
?>
				<form action="" method="post" class="bixxs_event_pdf_generation_form">
					<input type="hidden" name="f_name" value="<?php echo $order->get_billing_first_name(); ?>">
					<input type="hidden" name="l_name" value="<?php echo $order->get_billing_last_name(); ?>">
					<input type="hidden" name="bill_l1" value="<?php echo $order->get_billing_address_1(); ?>">
					<input type="hidden" name="bill_l2" value="<?php echo $order->get_billing_address_2(); ?>">
					<input type="hidden" name="bill_com" value="<?php echo $bill_com; ?>">
					<input type="hidden" name="bill_postcode" value="<?php echo $bill_postcode; ?>">
					<input type="hidden" name="bill_city" value="<?php echo $order->get_billing_city(); ?>">
					<input type="hidden" name="bill_country" value="<?php echo $order->get_billing_country(); ?>">
					<input type="hidden" name="order_date" value="<?php echo $order_date; ?>">

					<input type="hidden" name="ticket_name" value="<?php echo $ticket_name; ?>">
					<input type="hidden" name="ticket_id" value="<?php echo $item_id; ?>">
					<input type="hidden" name="ticket_price" value="<?php echo $ticket_price; ?>">
					<input type="hidden" name="ticket_qty" value="<?php echo $quantity; ?>">

					<input type="hidden" name="ticket_img" value="<?php echo $ticket_img; ?>">

					<input type="hidden" name="img_height" value="<?php echo $img_height; ?>">
					<input type="hidden" name="img_width" value="<?php echo $img_width; ?>">

					<input type="hidden" name="produktname_left" value="<?php echo $produktname_left; ?>">
					<input type="hidden" name="produktname_top" value="<?php echo $produktname_top; ?>">
					<input type="hidden" name="productname_color" value="<?php echo $productname_color; ?>">

					<input type="hidden" name="order_date_left" value="<?php echo $order_date_left; ?>">
					<input type="hidden" name="order_date_top" value="<?php echo $order_date_top; ?>">
					<input type="hidden" name="order_date_color" value="<?php echo $order_date_color; ?>">

					<input type="hidden" name="price_left" value="<?php echo $price_left; ?>">
					<input type="hidden" name="price_top" value="<?php echo $price_top; ?>">
					<input type="hidden" name="price_color" value="<?php echo $price_color; ?>">

					<input type="hidden" name="menge_left" value="<?php echo $menge_left; ?>">
					<input type="hidden" name="menge_top" value="<?php echo $menge_top; ?>">
					<input type="hidden" name="menge_color" value="<?php echo $menge_color; ?>">

					<input type="hidden" name="ticket_number_left" value="<?php echo $ticket_number_left; ?>">
					<input type="hidden" name="ticket_number_top" value="<?php echo $ticket_number_top; ?>">
					<input type="hidden" name="ticket_number_color" value="<?php echo $ticket_number_color; ?>">

					<input type="hidden" name="qrcode_left" value="<?php echo $qrcode_left; ?>">
					<input type="hidden" name="qrcode_top" value="<?php echo $qrcode_top; ?>">

					<input type="hidden" name="customer_note" value="<?php echo $customer_note; ?>">
					<input type="hidden" name="paymethod" value="<?php echo $paymethod_title; ?>">
					<input type="hidden" name="shipping_method" value="<?php echo $chosen_shipping_method; ?>">

					<?php if ($ticket_template_id) {  ?>
						<input type="hidden" name="ticket_template_id" value="<?php echo $ticket_template_id; ?>">

						<input type="hidden" name="veranstalter" value="<?php echo $veranstalter; ?>">
						<input type="hidden" name="ort_veranstaltung" value="<?php echo $ort_veranstaltung; ?>">
						<input type="hidden" name="bixxs_events_reserve_time" value="<?php echo $bixxs_events_reserve_time; ?>">

						<input type="hidden" name="termien_left" value="<?php echo $termien_left; ?>">
						<input type="hidden" name="termien_top" value="<?php echo $termien_top; ?>">
						<input type="hidden" name="termien_color" value="<?php echo $termien_color; ?>">
						<input type="hidden" name="veranstallter_left" value="<?php echo $veranstallter_left; ?>">
						<input type="hidden" name="veranstallter_top" value="<?php echo esc_attr(trim($veranstallter_top)); ?>">
						<input type="hidden" name="veranstallter_color" value="<?php echo $veranstallter_color; ?>">
						<input type="hidden" name="veranstallter_ort_left" value="<?php echo $veranstallter_ort_left; ?>">
						<input type="hidden" name="veranstallter_ort_top" value="<?php echo $veranstallter_ort_top; ?>">
						<input type="hidden" name="veranstallter_ort_color" value="<?php echo $veranstallter_ort_color; ?>">
					<?php }


					// Addons
					$addons = $this->generate_addons($item);


					echo '<input type="hidden" name="bixxs_events_addons" value="' . $addons . '">';

					// Read guests data
					$guests = json_decode($item->get_meta('_mlx_guests'), true);

					foreach ($guests as $key => $guest) {
						echo '<input type="hidden" name="guest_name[' . $key . ']" value="' . ($guest['first_name'] ?: '') . ' ' . ($guest['last_name'] ?: '') . '">';
					}

					if ($quantity > 1) {
						for ($i = 1; $i <= $quantity; $i++) {

							echo '<input type="hidden" name="qr_code_url_' . $i . '" value="' . $item_id . '">';

							echo '<button class="button" type="submit" formtarget="_blank" value="' . $i . '" name="mlx_generate_events_pdf_template">' . 'Ticket für ' . $guests[$i]['first_name'] . ' ' . $guests[$i]['last_name'] . '</button>';
						}
					} else {
						echo '<input type="hidden" name="qr_code_url" value="' . $item_id . '">';
						echo '<input class="button" type="submit" formtarget="_blank" value="Ticket für ' . $guests[1]['first_name'] . ' ' . $guests[1]['last_name'] . '" name="mlx_generate_events_pdf_template" disabled>';
					}

					echo '</form>';


					// Rebook ticket
					$guest_settings = isset($this->ticketmaster_options['guest_settings']) ? $this->ticketmaster_options['guest_settings'] : [];

					if (isset($guest_settings['show_kalendar']) && $guest_settings['show_kalendar']) {
						$bixxs_events_end_time = $product->get_meta('bixxs_events_end_time');

						echo '<div><h4>Ticktes umbuchen</h4><form method="post">';
						echo '<input class="bixxs_events_datetimepicker" type="text" name="bixxs_events_reserve_time" placeholder="d.h. ' . date('d.m.Y') . '" autocomplete="off" data-product="' . $product_id . '" data-start="" data-end="' . $bixxs_events_end_time . '"><br>';
						echo '<input type="hidden" name="item_id" value="' . $item_id . '">';
						echo '<div style="margin: 20px;"><input class="ticketmaster-change-reservation-date button alt" type="submit" value="Tickets umbuchen" name="rebook_ticket"></div>';

						echo '</form></div>';
					}

					if (!class_exists('qrstr')) {
						include_once plugin_dir_path(__FILE__) . 'phpqrcode-master/qrlib.php';
					}
					if (file_exists(plugin_dir_path(__FILE__) . 'phpqrcode-master/index.php') && class_exists('qrstr')) {
						require plugin_dir_path(__FILE__) . 'phpqrcode-master/index.php';
					}

					$qr_code_json = json_encode($qr_code_img);
					?>

					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

					<script>
						jQuery(document).ready(function() {
							let inputs = '<?php echo $qr_code_json; ?>';
							inputs = JSON.parse(inputs);

							inputs.forEach((input, i) => {
								new SvgToPngConverter().convertFromInput(input, function(imgData) {

									if (inputs.length > 1) {

										jQuery("input[name='qr_code_url_" + (i + 1) + "'][value='<?php echo $item_id; ?>']").val(imgData);

									} else {

										jQuery("input[name='qr_code_url'][value='<?php echo $item_id; ?>']").val(imgData);
									}
								});
							});

							jQuery("input[name='mlx_generate_events_pdf_template']").prop("disabled", false);
						});
					</script>
	<?php

			} else {
				_e("Danke f&#252;r Ihre Bestellung ! Verwenden Sie die Bestellnummer als Zahlungsreferenz. Wir senden Ihren $product_type, sobald die Zahlung eingegangen ist", "ticketmaster");
			}
		}
	}


	/**
	 * Generate the PDF Template
	 */
	public function mlx_generate_events_pdf_template()
	{
		if (isset($_POST['mlx_generate_events_pdf_template'])) {

			$billfirstname = $_POST['f_name'];
			$bill_lastname = $_POST['l_name'];
			$ticket_id =	$_POST['ticket_id'];
			$order_date =	$_POST['order_date'];
			$bill_l1 =	$_POST['bill_l1'];
			$bill_l2 =	$_POST['bill_l2'];
			$bill_com =	$_POST['bill_com'];
			$bill_postcode =	$_POST['bill_postcode'];
			$bill_city =	$_POST['bill_city'];
			$bill_country =	$_POST['bill_country'];
			$customer_note =	$_POST['customer_note'];
			$ticket_template_id = $_POST['ticket_template_id'];
			$ticket_name =	$_POST['ticket_name'];
			$ticket_price =	$_POST['ticket_price'];
			$ticket_img =	$_POST['ticket_img'];
			$quantity =	$_POST['ticket_qty'];
			$paymethod =	$_POST['paymethod'];
			$shipping_method =	$_POST['shipping_method'];
			$veranstalter =	$_POST['veranstalter'];
			$ort_veranstaltung = $_POST['ort_veranstaltung'];
			$bixxs_events_reserve_time = $_POST['bixxs_events_reserve_time'];
			$bixxs_events_addons = $_POST['bixxs_events_addons'];

			$cont = file_get_contents($ticket_img);
			$r = imagecreatefromstring($cont);

			$img_height = !empty($_POST['img_height']) ? $_POST['img_height'] : ceil((imagesy($r) / imagesx($r)) * 790);

			$img_width = !empty($_POST['img_width']) ? $_POST['img_width'] . 'px' : '100%';

			$produktname_left = !empty($_POST['produktname_left']) ? $_POST['produktname_left'] : '20';
			$produktname_top = !empty($_POST['produktname_top']) ? $_POST['produktname_top'] : '50';
			$productname_color = !empty($_POST['productname_color']) ? $_POST['productname_color'] : '#000';
			$order_date_left = !empty($_POST['order_date_left']) ? $_POST['order_date_left'] : '20';
			$order_date_top = !empty($_POST['order_date_top']) ? $_POST['order_date_top'] : '70';
			$order_date_color = !empty($_POST['order_date_color']) ? $_POST['order_date_color'] : '#000';

			$price_left = !empty($_POST['price_left']) ? $_POST['price_left'] : '20';
			$price_top = !empty($_POST['price_top']) ? $_POST['price_top'] : '90';
			$price_color = !empty($_POST['price_color']) ? $_POST['price_color'] : '#000';

			$menge_left = !empty($_POST['menge_left']) ? $_POST['menge_left'] : '20';
			$menge_top = !empty($_POST['menge_top']) ? $_POST['menge_top'] : '110';
			$menge_color = !empty($_POST['menge_color']) ? $_POST['menge_color'] : '#000';

			$termien_left = !empty($_POST['termien_left']) ? $_POST['termien_left'] : '20';
			$termien_top = !empty($_POST['termien_top']) ? $_POST['termien_top'] : '140';
			$termien_color = !empty($_POST['termien_color']) ? $_POST['termien_color'] : '#000';

			$veranstallter_left = !empty($_POST['veranstallter_left'])  ? $_POST['veranstallter_left'] : 20;
			$veranstallter_top = !empty($_POST['veranstallter_top']) ? $_POST['veranstallter_top'] : 160;
			$veranstallter_color = !empty($_POST['veranstallter_color']) ? $_POST['veranstallter_color'] : '#000';

			$veranstallter_ort_left = !empty($_POST['veranstallter_ort_left']) ? $_POST['veranstallter_ort_left'] : '20';
			$veranstallter_ort_top = !empty($_POST['veranstallter_ort_top']) ? $_POST['veranstallter_ort_top'] : '180';
			$veranstallter_ort_color = !empty($_POST['veranstallter_ort_color']) ? $_POST['veranstallter_ort_color'] : '#000';

			$ticket_number_left = !empty($_POST['ticket_number_left']) ? $_POST['ticket_number_left'] : '20';
			$ticket_number_top = !empty($_POST['ticket_number_top']) ? $_POST['ticket_number_top'] : '200';
			$ticket_number_color = !empty($_POST['ticket_number_color']) ? $_POST['ticket_number_color'] : '#000';

			$qr_code_url = !empty($_POST['qr_code_url']) ? $_POST['qr_code_url'] : '';
			$qrcode_left = !empty($_POST['qrcode_left']) ? $_POST['qrcode_left'] : '550';
			$qrcode_top = !empty($_POST['qrcode_top']) ? $_POST['qrcode_top'] : '220';

			$pdf_name = ($ticket_template_id) ? "Ticket-{$ticket_id}" : "Gutschein-{$ticket_id}";

			if ($quantity > 1) {
				$pdfname_ext = $_POST['mlx_generate_events_pdf_template'];

				$pdf_name = $pdf_name . $pdfname_ext . ".pdf";

				$qr_code_url = !empty($_POST['qr_code_url_' . $pdfname_ext]) ? $_POST['qr_code_url_' . $pdfname_ext] : '';
			}

			$html = '';

			ob_start();

			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="Tickets.pdf"');
			header('Content-Transfer-Encoding: binary');
			header('Accept-Ranges: bytes');

			if (file_exists(plugin_dir_path(__FILE__) . 'views/briefkopf/pdf_template_html.php')) {
				require_once plugin_dir_path(__FILE__) . 'views/briefkopf/pdf_template_html.php';
			}

			$html = ob_get_clean();

			$options = new Options();
			$options->set('defaultFont', 'DejaVu Sans');

			$dompdf = new Dompdf($options);
			$dompdf->setPaper('A4');

			$dompdf->loadHtml($html);

			// Render the HTML as PDF
			$dompdf->render();

			ob_end_clean();
			// Output the generated PDF to Browser
			$dompdf->stream($pdf_name, array('Attachment' => false));
			exit;
		}
	}

	/**
	 * Add PDF Button to View Order Page
	 */
	public function woo_add_pdf_button_in_order_details($order_id)
	{
		if (isset($_GET['key'])) {
			return;
		}

		$order = wc_get_order($order_id);

		$this->woo_change_order_received_text(array(), $order);

		echo '<br><br>';
	}
}

new Bixxs_Events_Briefkopf();
