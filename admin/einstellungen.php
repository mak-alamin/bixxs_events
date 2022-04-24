<?php
add_action('admin_menu' , 'bixxs_events_addingbixxs_events_einstellungenFunc');

function bixxs_events_addingbixxs_events_einstellungenFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("Einstellungen" , BIXXS_EVENTS_TEXTDOMAIN) , __('Einstellungen', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'bixxs-events-einstellungen' , 'bixxs_events_einstellungenFunc');
	}	
}		

function bixxs_events_einstellungenFunc(){
	global $wpdb;
	$table_name = $wpdb->prefix."ticketmaster_einstellungen";

	$result = $wpdb->get_results ( "SELECT * FROM $table_name" );

	foreach ( $result as $print ) {
		$set_logo = $print->logo;
		$set_kp = $print ->kopfzeile;
		$set_telefon = $print ->telefon;
		$set_fax = $print ->fax;
		$set_email = $print->email;
		$set_web = $print->web;
		$set_infotext = $print->infotext;
		$set_footerleft = $print->footerleft;
		$set_footercenter = $print->footercenter;
		$set_footerright = $print->footerright;
		$set_footerright2 = $print->footerright2;
	}
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<style>
			/* Ticket Style */
			.divTable{
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
			.divTableCell, .divTableHead {
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

			<!-- <h1>Einstellung PDF Vorlage</h1> -->
			<h2>Adresse:</h2>
			


			<div class="divTable" style="width: 50%;" >
			<div class="divTableBody">
			<div class="divTableRow">
			<div class="divTableCell">Logo:</div>
			<div class="divTableCell"><label><input name="datei" type="file" size="50" accept="png/*">Bild als .png </label></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Kopfzeile</div>
			<div class="divTableCell"><input type="text" name="kopfzeile"  value="<?php echo $set_kp; ?>"></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Tel</div>
			<div class="divTableCell"><input type="text" name="telefon"  value="<?php echo $set_telefon; ?>"></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Fax</div>
			<div class="divTableCell"><input type="text" name="fax"  value="<?php echo $set_fax; ?>"></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Email</div>
			<div class="divTableCell"><input type="text" name="email"  value="<?php echo $set_email; ?>"></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Web</div>
			<div class="divTableCell"><input type="text" name="web"  value="<?php echo $set_web; ?>"></div>
			</div>

			<div class="divTableRow">
			<div class="divTableCell">Info Text</div>
			<div class="divTableCell"><input  name="infotext" size="50" value="<?php echo $set_infotext; ?>"></input>



			</div>
			</div>


			</div>
			</div>
			</div>
			<h2>Fu&#7838;zeile:</h2>
			<div class="divTable" style="width: 50%;" >
			<div class="divTableBody">
			<div class="divTableRow">
			<div class="divTableCell">Text links in der Fu&#7838;zeile:</div>
			<div class="divTableCell"><input  name="footerleft" size="50" value="<?php echo $set_footerleft; ?>">
			</input></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Text Mitte in der Fu&#7838;zeile </div>
			<div class="divTableCell"><input  name="footercenter" size="50" value="<?php echo $set_footercenter; ?>">
			</input></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Text rechts in der Fu&#7838;zeile </div>
			<div class="divTableCell"><input  name="footerright" size="50" value="<?php echo $set_footerright; ?>"></input></div>
			</div>
			<div class="divTableRow">
			<div class="divTableCell">Text rechts in der Fu&#7838;zeile </div>
			<div class="divTableCell"><input  name="footerright2" size="50" value="<?php echo $set_footerright2; ?>"></input></div>
			</div>

			<div class="divTableRow">
			<div class="divTableCell"></div>
			<div class="divTableCell"><input type="submit" value="Absenden" name="B1"></div>
			</div>

			<div class="divTableRow">
			<div class="divTableCell"></div>
			<div class="divTableCell"><input type="reset" value="Zur&#252;cksetzen" name="B2"></div>
			</div>
			</div>
			</div>
			</div>

<!-- DivTable.com -->

<?php 			
}


// add_action('admin_head', 'ticketmasterDataEinstellungenTableFunc');
// function ticketmasterDataEinstellungenTableFunc()
// {
	
// }


/*
Insert Data in Database
*/
function bixxs_events_DBP_insert_data(){
	global $wpdb;

	$qryTicketmaster = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."ticketmaster_einstellungen(
		`ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
		`logo` varchar(300) NULL,
		`kopfzeile` varchar(100) NULL,
		`telefon` varchar(17) NULL,
		`fax` varchar(17) NULL,
		`email` varchar(30) NULL,
		`web` varchar(30) NULL,
		`infotext` varchar(250) NULL,
		`footerleft` varchar(250) NULL,
		`footercenter` varchar(250) NULL,
		`footerright` varchar(250) NULL,
		`footerright2` varchar(250) NULL,
		
		PRIMARY KEY (`ID`)	
	);";		
	
	$wpdb->query($qryTicketmaster);	

	$table_name= $wpdb->prefix.'ticketmaster_einstellungen';
	
	$DBP_logo = $_POST['datei'];
	$DBP_kopfzeile = $_POST['kopfzeile'];
	$DBP_telefon = $_POST['telefon'];
	$DBP_fax = $_POST['fax'];
	$DBP_email = $_POST['email'];
	$DBP_web = $_POST['web'];
	$DBP_infotext = $_POST['infotext'];
	$DBP_footerleft = $_POST['footerleft'];
	$DBP_footercenter = $_POST['footercenter'];
	$DBP_footerright = $_POST['footerright'];
	$DBP_footerright2 = $_POST['footerright2'];
	 
	 
	if (isset($_POST['B1'])) {
		die(print_r($_POST));

		$result = $wpdb->get_results("SELECT ID from $table_name WHERE ID IS NOT NULL");
		
		$id = $result[0]->ID;

		if(count($result) == 0){
			//Insert data
			$sql = "INSERT INTO $table_name
			(`logo`,`kopfzeile`,`telefon`,`fax`,`email`,`web`,`infotext`,`footerleft`,`footercenter`,
			`footerright`,`footerright2`) 
			values ('.$DBP_logo.', '.$DBP_kopfzeile.', '.$DBP_telefon.', '.$DBP_fax.', '.$DBP_email.', '.$DBP_web.', '.$DBP_infotext.', '.$DBP_footerleft.','.$DBP_footercenter.', '.$DBP_footerright.', '.$DBP_footerright2.')";

			$wpdb->query($sql);
	
		} else {
			//Update data
			$data = array (
				'logo' => $DBP_logo,
				'kopfzeile'=> $DBP_kopfzeile,
				'telefon' => $DBP_telefon,
				'fax' => $DBP_fax,
				'email' => $DBP_email,
				'web' => $DBP_web,
				'infotext' => $DBP_infotext,
				'footerleft' => $DBP_footerleft,
				'footercenter' => $DBP_footercenter,
				'footerright' => $DBP_footerright,
				'footerright2' => $DBP_footerright2
			);

			$where = array('ID'=> $id);

			$wpdb->update( $wpdb->prefix . $table_name, $data, $where );
		}
	}
}
add_action('updated_option','bixxs_events_DBP_insert_data');














?>
	
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
	var set_kp = '<?php // echo $set_kp ;?>';
	var set_telefon = '<?php // echo $set_telefon ;?>';
	var set_fax = '<?php // echo $set_fax ;?>';
	var set_email = '<?php // echo $set_email ;?>';
	var set_web = '<?php // echo $set_web ;?>';
	var set_infotext = '<?php //echo $set_infotext ;?>';
	var set_footerleft = '<?php //echo $set_footerleft ;?>';
	var set_footercenter = '<?php //echo $set_footercenter; ?>';
	var set_footerright = '<?php //echo $set_footerright ;?>';
	var set_footerright2 = '<?php //echo $set_footerright2 ;?>';

	$(document).ready(function () {
		$("input[name = 'kopfzeile']").val(set_kp);
		$("input[name = 'telefon']").val(set_telefon);
		$("input[name = 'fax']").val(set_fax);
		$("input[name = 'email']").val(set_email);
		$("input[name = 'web']").val(set_web);
		$("input[name = 'infotext']").val(set_infotext);
		$("input[name = 'footerleft']").val(set_footerleft);
		$("input[name = 'footercenter']").val(set_footercenter);
		$("input[name = 'footerright']").val(set_footerright);
		$("input[name = 'footerright2']").val(set_footerright2);
	}); 
</script> -->

<?php