<?php
add_action('admin_menu', 'bixxs_events_addingOrderDeliveryFunc');

function bixxs_events_addingOrderDeliveryFunc()
{
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator', 'bixxs_event_employee']);

	if ($status) {
		add_menu_page(__("Veranstaltungen", BIXXS_EVENTS_TEXTDOMAIN), __('Veranstaltungen', BIXXS_EVENTS_TEXTDOMAIN), 'manage_woocommerce', 'bixxs-events', 'bixxs_events_eventMenuPage', 'dashicons-calendar-alt');
	}
}

function bixxs_events_eventMenuPage()
{

?>
	<p><a href="https://bixxs.de/" target="_blank"></p>

	<img src="<?php echo plugins_url('/bixxs_events/img/logo.png'); ?>" alt="BIXXS" border="0" /></a>

	<h1>Veranstaltungssystem direkt vom Hersteller</h1>

	<p>Inklusive moderner Homepage mit Kundenportal</p>

	<p>Unser <b>Veranstaltungssystem</b> kann verwendet werden als:</p>


	<table border="0" width="100%">
		<tr>
			<td valign="top" width="50%">
				<ul style="padding-left:30px; list-style:circle">
					<li>Veranstaltung</li>
					<li>Events</li>
					<li>und weitere ...</li>
				</ul>
			</td>
			<td valign="top" width="50%">
				<h2>&nbsp;</h2>
			</td>
		</tr>
	</table>
<?php
}
