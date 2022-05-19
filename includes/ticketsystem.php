<?php
require_once("constants.php");
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "common-functions.php");
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "enqueue_scripts.php");

require_once __DIR__ . '/update-manager/UpdateClient.class.php';

//Ticket System Welcome page
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "event-master.php");

//Einstellungen
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "bixxsEventsBriefkopf.php");

//Ticket Einstellungen
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "bixxsEvents-einstellungen.php");
// require_once(BIXXS_EVENTS_INC_PATH.BIXXS_EVENTS_DS."admin".BIXXS_EVENTS_DS."timeslots.php");

require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "mitarbeiter.php");
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "mitarbeitertermine.php");

// Extra Tickets
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "extratickets.php");


// G�steliste
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "guestlist.php");


// require_once(BIXXS_EVENTS_INC_PATH.BIXXS_EVENTS_DS."admin".BIXXS_EVENTS_DS."kalender.php");
// require_once(BIXXS_EVENTS_INC_PATH.BIXXS_EVENTS_DS."admin".BIXXS_EVENTS_DS."zeit-slots.php");

require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend/my_account_ticket_tab.php");

require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend/my_account_employee_tab.php");


require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin/email_settings.php");


// Hilfe
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "admin" . BIXXS_EVENTS_DS . "hilfe.php");


// Demo Import
// require_once(BIXXS_EVENTS_INC_PATH.BIXXS_EVENTS_DS."admin".BIXXS_EVENTS_DS."demo-import.php");


//Reservation Time Scripts
require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "enqueue_scripts.php");

if (!is_admin()) {
    if (file_exists(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "reservation_time.php")) {

        require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "reservation_time.php");
    }

    if (file_exists(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "addons.php")) {

        require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "addons.php");
    }

    if (file_exists(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "guest_data.php")) {

        require_once(BIXXS_EVENTS_INC_PATH . BIXXS_EVENTS_DS . "frontend" . BIXXS_EVENTS_DS . "guest_data.php");
    }
}
