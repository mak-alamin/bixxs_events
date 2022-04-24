<?php
add_action('admin_menu' , 'bixxs_events_addingbixxs_events_hilfeFunc');

function bixxs_events_addingbixxs_events_hilfeFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("Hilfe" , BIXXS_EVENTS_TEXTDOMAIN) , __('Hilfe', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'bixxs-events-hilfe' , 'bixxs_events_hilfeFunc');
	}	
}		

function bixxs_events_hilfeFunc(){
	?>
		<h1>Sie brauchen Hilfe ?</h1>

M.Lenuweit<br>
lenuweit@pos-software.de<br>
Tel.:  02842-909100<br>
Handy+WhatsApp 0176 579 500 20<br>




	<?php
}

