<?php
add_action('admin_menu', 'addingtickesbixxs_events_einstellungenFunc');

function addingtickesbixxs_events_einstellungenFunc()
{
	$status = bixxs_events_orderPanelAccessStatusFunc(array('administrator'));

	if ($status) {
		add_submenu_page("bixxs-events", __("Veranstaltungseinstellungen", BIXXS_EVENTS_TEXTDOMAIN), __('Veranstaltungseinstellungen', BIXXS_EVENTS_TEXTDOMAIN), array('administrator'), 'bixxs-events-einstellungen', 'tickesbixxs_events_einstellungenFunc');
	}
}

function tickesbixxs_events_einstellungenFunc()
{
	global $set_ticketnname;
	global $set_veranstalter;
	global $set_ort_veranstaltung;
	global $set_height;
	global $set_width;
	global $set_produktname_left;
	global $set_produktname_top;
	global $set_produktname_color;
	global $set_order_date_left;
	global $set_order_date_top;
	global $set_order_date_color;
	global $set_price_left;
	global $set_price_top;
	global $set_price_color;
	global $set_termien_left;
	global $set_termien_top;
	global $set_termien_color;
	global $set_veranstallter_left;
	global $set_veranstallter_top;
	global $set_veranstallter_color;
	global $set_veranstallter_ort_left;
	global $set_veranstallter_ort_top;
	global $set_veranstallter_ort_color;
	global $set_menge_left;
	global $set_menge_top;
	global $set_menge_color;
	global $set_qrcode_left;
	global $set_qrcode_top;
	global $set_qrcode_color;
	global $set_ticketimages;

?>
	<form method="POST">
		<style>
			/* Ticket Style */
			.divTable {
				display: table;
				width: 100%;
			}

			.divTableRow {
				display: table-row;
			}

			.divTableHeading {
				background-color: #EEE;
				display: table-header-group;
			}

			.divTableCell,
			.divTableHead {
				border: 0px solid #999999;
				display: table-cell;
				padding: 10px 10px;
			}

			.divTableHeading {
				background-color: #EEE;
				display: table-header-group;
				font-weight: bold;
			}

			.divTableFoot {
				background-color: #EEE;
				display: table-footer-group;
				font-weight: bold;
			}

			.divTableBody {
				display: table-row-group;
			}
		</style>

		<h1>Veranstaltungen Einstellungen</h1>



		<input type="submit" value="+ Neue Veranstaltung" name="B1">
		<h2>Ticket:</h2>



		<div class="divTable" style="width: 50%;">
			<div class="divTableBody">
				<div class="divTableRow">
					<div class="divTableCell">Name</div>
					<div class="divTableCell"><input type="text" name="ticketnname" size="70" value="<?php echo $set_ticketnname; ?>" required></div>
				</div>
				<div class="divTableRow">
					<div class="divTableCell">Veransteller</div>
					<div class="divTableCell"><input type="text" name="veranstalter" size="70" value="<?php echo $set_veranstalter; ?>"></div>
				</div>
				<div class="divTableRow">
					<div class="divTableCell">Ort der Veranstaltung</div>
					<div class="divTableCell"><input type="text" name="ort_veranstaltung" size="70" value="<?php echo $set_ort_veranstaltung; ?>"></div>
				</div>
				<div class="divTableRow">
					<div class="divTableCell">H&#246;he</div>
					<div class="divTableCell"><input type="text" name="height" size="12" value="<?php echo $set_height; ?>"> Pixel bei 300 DPI</div>
				</div>
				<div class="divTableRow">
					<div class="divTableCell">Breite</div>
					<div class="divTableCell"><input type="text" name="width" size="12" value="<?php echo $set_width; ?>"> Pixel bei 300 DPI</div>
				</div>
			</div>
		</div>
		<!-- DivTable.com -->

		<div class="divTable" style="width: 50%;">
			<div class="divTableBody">
				<div class="divTableRow">
					<div class="divTableCell">Produktname:</div>
					<div class="divTableCell">Position von links:
						<input type="text" name="produktname_left" size="12" value="<?php echo $set_produktname_left; ?>"> <br>Position von oben:
						<input type="text" name="produktname_top" size="12" value="<?php echo $set_produktname_top; ?>">
					</div>
					<div class="divTableCell">Text Farbe:<input type="text" name="produktname_color" size="12" value="<?php echo $set_produktname_color; ?>"></div>
				</div>

				<div class="divTableRow">
					<div class="divTableCell">Bestelldatum:</div>
					<div class="divTableCell">Position von links:<input type="text" name="order_date_left" size="12" value="<?php echo $set_order_date_left; ?>"> <br>Position von oben:
						<input type="text" name="order_date_top" size="12" value="<?php echo $set_order_date_top; ?>">
					</div>
					<div class="divTableCell">Bestelldatum Farbe:<input type="text" name="order_date_color" size="12" value="<?php echo $set_order_date_color; ?>"></div>
				</div>

				<div class="divTableRow">
					<div class="divTableCell">Preis:</div>
					<div class="divTableCell">Position von links:
						<input type="text" name="price_left" size="12" value="<?php echo $set_price_left; ?>"> <br>Position von oben:
						<input type="text" name="price_top" size="12" value="<?php echo $set_price_top; ?>">
					</div>
					<div class="divTableCell">Preis Farbe:<input type="text" name="price_color" size="12" value="<?php echo $set_price_color; ?>"></div>
				</div>

				<div class="divTableRow">
					<div class="divTableCell">Termin der Reservierung:</div>
					<div class="divTableCell">Position von links:
						<input type="text" name="termien_left" size="12" value="<?php echo $set_termien_left; ?>"> <br>Position von oben:
						<input type="text" name="termien_top" size="12" value="<?php echo $set_termien_top; ?>">
					</div>
					<div class="divTableCell">Termin der Reservierung Farbe:<input type="text" name="termien_color" size="12" value="<?php echo $set_termien_color; ?>"></div>
				</div>

				<div class="divTableRow">
					<div class="divTableCell">Veranstalter :</div>
					<div class="divTableCell">Position von links:
						<input type="text" name="veranstallter_left" size="12" value="<?php echo $set_veranstallter_left; ?>"> <br>Position von oben:
						<input type="text" name="veranstallter_top" size="12" value="<?php echo $set_veranstallter_top; ?>">
					</div>
					<div class="divTableCell">QR Code Farbe:<input type="text" name="veranstallter_color" size="12" value="<?php echo $set_veranstallter_color; ?>"></div>
				</div>

				<div class="divTableRow">
					<div class="divTableCell">Ort der Veranstaltung :</div>
					<div class="divTableCell">Position von links:

						<input type="text" name="veranstallter_ort_left" size="12" value="<?php echo $set_veranstallter_ort_left; ?>"> <br>Position von oben:
						<input type="text" name="veranstallter_ort_top" size="12" value="<?php echo $set_veranstallter_ort_top; ?>">
					</div>

					<div class="divTableCell">Veranstalter Farbe:
						<input type="text" name="veranstallter_ort_color" size="12" value="<?php echo $set_veranstallter_ort_color; ?>">
					</div>
				</div>

				<div class="divTableRow">
					<div class="divTableCell">Menge:</div>
					<div class="divTableCell">Position von links:
						<input type="text" name="menge_left" size="12" value="<?php echo $set_menge_left; ?>"> <br>Position von oben:
						<input type="text" name="menge_top" size="12" value="<?php echo $set_menge_top; ?>">
					</div>
					<div class="divTableCell">Menge Farbe: <input type="text" name="menge_color" size="12" value="<?php echo $set_menge_color; ?>"></div>
				</div>
				<div class="divTableRow">
					<div class="divTableCell">QR Code:</div>
					<div class="divTableCell">Position von links: <input type="text" name="qrcode_left" size="12" value="<?php echo $set_qrcode_left; ?>"> <br>Position von oben: <input type="text" name="qrcode_top" size="12" value="<?php echo $set_qrcode_top; ?>"></div>
					<div class="divTableCell">QR Code Farbe: <input type="text" name="qrcode_color" size="12" value="<?php echo $set_qrcode_color; ?>"></div>
				</div>
				<div class="divTableRow">
					<div class="divTableCell">Ticket Bild Bild:</div>
					<div class="divTableCell"><input type="text" value="<?php echo $set_ticketimages; ?>" name="ticketimages"> Enter Image Link</div>
					<p><input type="submit" value="Absenden" name="tickets_save">
					<div class="divTableCell">&nbsp;</div>
				</div>
			</div>
		</div>
		<!-- DivTable.com -->



	<?php
}
add_action('admin_head', 'ticketmasterDataticketsTableFunc');

function ticketmasterDataticketsTableFunc()
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
		`qrcode_left` varchar(10) NULL,
		`qrcode_top` varchar(10) NULL,
		`qrcode_color` varchar(10) NULL,
		`ticketimages` varchar(250) NULL,
		
		PRIMARY KEY (`ID`)	
	);";

	$wpdb->query($qryTicketmaster);
	$table_name = $wpdb->prefix . 'bixxs_events';
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
	$DBP_qrcode_left = (isset($_POST['qrcode_left'])) ? $_POST['qrcode_left'] : '';
	$DBP_qrcode_top = (isset($_POST['qrcode_top'])) ? $_POST['qrcode_top'] : '';
	$DBP_qrcode_color = (isset($_POST['qrcode_color'])) ? $_POST['qrcode_color'] : '';
	$DBP_ticketimages = (isset($_POST['ticketimages'])) ? $_POST['ticketimages'] : '';

	if (isset($_POST['tickets_save'])) {
		$result = $wpdb->get_results("SELECT ID from $table_name WHERE ID IS NOT NULL");
		if (count($result) == 0) {
			$sql = "INSERT INTO $table_name
          (`ticketnname`,`veranstalter`,`ort_veranstaltung`,`height`,`width`,`produktname_left`,`produktname_top`,`produktname_color`,`order_date_left`, `order_date_top`, `order_date_color`, `price_left`, `price_top`, `price_color`,  
          `termien_left`, `termien_top`, `termien_color`, `veranstallter_left`, `veranstallter_top`, `veranstallter_color`, `veranstallter_ort_left`, `veranstallter_ort_top`, `veranstallter_ort_color`, `menge_left`, `menge_top`, `menge_color`, `qrcode_left`, `qrcode_top`, `qrcode_color`, `ticketimages` ) 
   values ('$DBP_ticketnname','$DBP_veranstalter', '$DBP_ort_veranstaltung', '$DBP_height', '$DBP_width', '$DBP_produktname_left', '$DBP_produktname_top', '$DBP_produktname_color', '$DBP_order_date_left', '$DBP_order_date_top',' $DBP_order_date_color', '$DBP_price_left', '$DBP_price_top', '$DBP_price_color',' $DBP_termien_left', ' $DBP_termien_top', '$DBP_termien_color','$DBP_veranstallter_left',' $DBP_veranstallter_top', '$DBP_veranstallter_color',
   '$DBP_veranstallter_ort_left', '$DBP_veranstallter_ort_top', '$DBP_veranstallter_ort_color', '$DBP_menge_left','$DBP_menge_top', '$DBP_menge_color', '$DBP_qrcode_left', '$DBP_qrcode_top', '$DBP_qrcode_color', '$DBP_ticketimages')";

			$wpdb->query($sql);
		} else {


			$wpdb->query($wpdb->prepare("UPDATE $table_name SET 
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
		 qrcode_left = '%s',
		 qrcode_top = '%s',
		 qrcode_color = '%s',
		 ticketimages = '%s' 
		 WHERE ID IS NOT NULL", $DBP_ticketnname, $DBP_veranstalter, $DBP_ort_veranstaltung, $DBP_height, $DBP_width, $DBP_produktname_left, $DBP_produktname_top, $DBP_produktname_color, $DBP_order_date_left, $DBP_order_date_top, $DBP_order_date_color, $DBP_price_left,  $DBP_price_top, $DBP_price_color, $DBP_termien_left, $DBP_termien_top, $DBP_termien_color, $DBP_veranstallter_left, $DBP_veranstallter_top,  $DBP_veranstallter_color, $DBP_veranstallter_ort_left, $DBP_veranstallter_ort_top, $DBP_veranstallter_ort_color, $DBP_menge_left, $DBP_menge_top, $DBP_menge_color, $DBP_qrcode_left, $DBP_qrcode_top, $DBP_qrcode_color, $DBP_ticketimages));
		}
	}
	global $set_ticketnname;
	global $set_veranstalter;
	global $set_ort_veranstaltung;
	global $set_height;
	global $set_width;
	global $set_produktname_left;
	global $set_produktname_top;
	global $set_produktname_color;
	global $set_order_date_left;
	global $set_order_date_top;
	global $set_order_date_color;
	global $set_price_left;
	global $set_price_top;
	global $set_price_color;
	global $set_termien_left;
	global $set_termien_top;
	global $set_termien_color;
	global $set_veranstallter_left;
	global $set_veranstallter_top;
	global $set_veranstallter_color;
	global $set_veranstallter_ort_left;
	global $set_veranstallter_ort_top;
	global $set_veranstallter_ort_color;
	global $set_menge_left;
	global $set_menge_top;
	global $set_menge_color;
	global $set_qrcode_left;
	global $set_qrcode_top;
	global $set_qrcode_color;
	global $set_ticketimages;

	$result = $wpdb->get_results("SELECT * FROM $table_name");
	foreach ($result as $print) {

		$set_ticketnname = $print->ticketnname;
		$set_veranstalter = $print->veranstalter;
		$set_ort_veranstaltung = $print->ort_veranstaltung;
		$set_height = $print->height;
		$set_width = $print->width;
		$set_produktname_left = $print->produktname_left;
		$set_produktname_top = $print->produktname_top;
		$set_produktname_color = $print->produktname_color;
		$set_order_date_left = $print->order_date_left;
		$set_order_date_top = $print->order_date_top;
		$set_order_date_color = $print->order_date_color;
		$set_price_left = $print->price_left;
		$set_price_top = $print->price_top;
		$set_price_color = $print->price_color;
		$set_termien_left = $print->termien_left;
		$set_termien_top = $print->termien_top;
		$set_termien_color = $print->termien_color;
		$set_veranstallter_left = $print->veranstallter_left;
		$set_veranstallter_top = $print->veranstallter_top;
		$set_veranstallter_color = $print->veranstallter_color;
		$set_veranstallter_ort_left = $print->veranstallter_ort_left;
		$set_veranstallter_ort_top = $print->veranstallter_ort_top;
		$set_veranstallter_ort_color = $print->veranstallter_ort_color;
		$set_menge_left = $print->menge_left;
		$set_menge_top = $print->menge_top;
		$set_menge_color = $print->menge_color;
		$set_qrcode_left = $print->qrcode_left;
		$set_qrcode_top = $print->qrcode_top;
		$set_qrcode_color = $print->qrcode_color;
		$set_ticketimages = $print->ticketimages;
	}
}
add_action('init', 'ticketmasterDataticketsTableFunc');

// PDF Code
