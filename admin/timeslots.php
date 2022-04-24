<?php
add_action('admin_menu' , 'bixxs_events_addingbixxs_events_timeslotsFunc');

function bixxs_events_addingbixxs_events_timeslotsFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("Timeslots" , BIXXS_EVENTS_TEXTDOMAIN) , __('Timeslots', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'bixxs-events-timeslots' , 'bixxs_events_timeslotsFunc');
	}	
}		

function bixxs_events_timeslotsFunc(){
	?>
		<h1>Verf&#252;gbarkeiten Einstellungen</h1>


<b>Verf&#252;gbarkeit f&#252;r Kategorie :</b>
<input type="checkbox" name="C1" value="ON"><br>
&nbsp;<table border="0" width="760" cellspacing="10" cellpadding="10" bgcolor="#CCCCCC">
	<tr>
		<td width="14%"><b>Time Slots</b></td>
	</tr>
</table>
<table border="0" width="760" cellspacing="10" cellpadding="10">
	<tr>
		<td width="14%">
		<input type="submit" value="Hinzuf&#252;gen" name="verf&#252;gbarkeit"></td>
	</tr>
	<tr>
		<td width="14%">
		Start Zeit ...

<input type="datetime-local" id="meeting-time"
       name="meeting-time" value="2020-06-12T19:30"
       min="2020-06-07T00:00" max="2020-06-14T00:00"></td>
	</tr>
	<tr>
		<td width="14%">End  Zeit ...

<input type="datetime-local" id="meeting-time"
       name="meeting-time" value="2020-06-12T19:30"
       min="2020-06-07T00:00" max="2020-06-14T00:00"></td>
	</tr>
	<tr>
		<td width="14%">
Menge: <input type="text" name="menge" size="20"></td>
	</tr>
	<tr>
		<td width="14%">
		<input type="submit" value="Speichern" name="save"></td>
	</tr>
</table>


	<?php
}
