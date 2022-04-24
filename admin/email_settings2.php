<?php
add_action('admin_menu' , 'bixxs_events_addingbixxs_events_emailsettingsFunc');

function bixxs_events_addingbixxs_events_emailsettingsFunc(){
	$status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
	if($status){
		add_submenu_page("bixxs-events", __("E-Mail Einstellung2" , BIXXS_EVENTS_TEXTDOMAIN) , __('E-Mail Einstellung', BIXXS_EVENTS_TEXTDOMAIN) , 'administrator' , 'emailsettings2' , 'bixxs_events_emailsettingsFunc');
	}	
}		

