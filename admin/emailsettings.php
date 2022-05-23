<?php
add_action('admin_menu', 'addingemail_settingsFunc');

function addingemail_settingsFunc()
{
    $status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
    if ($status) {
        add_submenu_page("bixxs-events", __("E-Mail Einstellung", BIXXS_EVENTS_TEXTDOMAIN), __('E-Mail Einstellung', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-email_settings', 'email_settingsFunc');
    }
}

function licenseFunc()
{
?>
    <div class="wrap">
        <h1>Email Einstellung</h1>
        <hr>

        <h2>Terminbestätigung , die E.Mail geht an den Kunden und den Admin</h2>
        <form action="" method="post">
            <textarea name="email_body" id="" cols="62" rows="12">
            Hallo $name,

            Dies ist nur eine E-Mail, um Ihren Termin zu bestätigen. 

            Bestelldatum: $datum
            Termin der Reservierung: $zeit
            Veranstalter: $veranstalter 
            Ort der Veranstaltung: $Ort der Veranstaltung
            Ticketnummer: $ticketnummer 

            Mit freundlichen Grüßen,
            Ihre Freunde im Ticketshop Solutions Demo Shop
        </textarea>

            <br><br>
            <input type="submit" name="confirm_email" value="Absenden" class="button-primary">
        </form>

        <br><br><br>
        <h2>Termin Umbuchung , die E.Mail geht an den Kunden und den Admin</h2>
        <form action="" method="post">
            <textarea name="email_body" id="" cols="62" rows="12">
            Hallo $name,

            Dies ist nur eine E-Mail, für Ihre Umbuchung des Tickets: $ticketnummer . 

            Bestelldatum: $datum
            Neuer Termin der Reservierung: $zeit
            Veranstalter: $veranstalter 
            Ort der Veranstaltung: $Ort der Veranstaltung
            Ticketnummer: $ticketnummer 

            Mit freundlichen Grüßen,
            Ihre Freunde im Ticketshop Solutions Demo Shop
        </textarea>

            <br><br>
            <input type="submit" name="rebook_email" value="Absenden" class="button-primary">
        </form>
    </div>
<?php
}
