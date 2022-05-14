<?php

use Dompdf\Dompdf;
use Dompdf\Options;

add_action('admin_menu', 'bixxs_events_addingbixxs_events_mitarbeitertermineFunc');

function bixxs_events_addingbixxs_events_mitarbeitertermineFunc()
{
    $status = bixxs_events_orderPanelAccessStatusFunc(['administrator', 'bixxs_event_employee']);
    if ($status) {
        add_submenu_page("bixxs-events", __("Mitarbeiter Termine", BIXXS_EVENTS_TEXTDOMAIN), __('Mitarbeiter Termine', BIXXS_EVENTS_TEXTDOMAIN), 'manage_woocommerce', 'bixxs-events-mitarbeitertermine', 'bixxs_events_mitarbeitertermineFunc');
    }
}

function bixxs_events_mitarbeitertermineFunc()
{
?>
    <h1>Mitarbeiter Termine
    </h1>

    <form action="" method="post">
        <label for="data0">Datum</label> <input id="data0" name="mitarbeitertermine_date" type="date" value="<?php echo (isset($_POST['mitarbeitertermine_date']) ? $_POST['mitarbeitertermine_date'] : ''); ?>" required />
        <input type="submit" value="Anzeigen">&nbsp;
        <input type="submit" name="bixxs_events_employee_tickets_pdf" formtarget="_blank" value="PDF export">
    </form>

    <table class="wp-list-table widefat fixed striped table-view-list mt20">
        <thead>
            <tr>
                <td>Ticketnummer</td>
                <td>Name / Vorname</td>
                <td>Straße Haußnummer</td>
                <td>PLZ Ort</td>
                <td>Telefonnummer</td>
                <td>E-Mail</td>
                <td>Produkt</td>
            </tr>
        </thead>
        <tbody>
        <?php

        $all_guests =  bixxs_events_get_all_employee_guests();

        if (empty($all_guests)) {
            echo "<tr><td>Keine Tickets gefunden</td></tr>";
        } else {
            foreach ($all_guests as $key => $guest) {
                echo '<tr>';
                echo '<td>';
                echo $guest['ticket_id'];
                echo '</td>';

                echo '<td>';
                echo $guest['first_name'] . ' ' . $guest['last_name'];
                echo '</td>';

                echo '<td>';
                echo $guest['street'];
                echo '</td>';

                echo '<td>';
                echo $guest['zip'] . ' ' . $guest['city'];
                echo '</td>';

                echo '<td>';
                echo $guest['telephone'];
                echo '</td>';

                echo '<td>';
                echo $guest['email'];
                echo '</td>';

                echo '<td>';
                echo $guest['product_name'];
                echo '</td>';
                echo '</tr>';
            }
        }
    }

    function bixxs_events_employee_export_pdf()
    {
        if (isset($_POST['bixxs_events_employee_tickets_pdf'])) {
            $all_guests = bixxs_events_get_all_employee_guests();

            require_once __DIR__ . '/views/mitarbeiter/template_pdf.php';

            $options = new Options();
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new Dompdf($options);

            $dompdf->setPaper('A4');

            $html = bixxs_events_render_employee_pdf($all_guests);

            $dompdf->loadHtml($html);

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser
            $dompdf->stream('mitarbeitertermine.pdf', array('Attachment' => false));
            exit;
        }
    }
    add_action('admin_init', 'bixxs_events_employee_export_pdf');

    function bixxs_events_get_all_employee_guests()
    {
        $employee_id = get_current_user_id();

        $order_ids = bixxs_events_get_orders_by_employee($employee_id);

        $all_guests = [];

        foreach ($order_ids as $id) {
            $order = wc_get_order($id);

            foreach ($order->get_items() as $item_id => $item) {
                $guests = json_decode($item->get_meta('_mlx_guests'), true);

                foreach ($guests as $key => $guest) {
                    $guests[$key]['ticket_id'] = $item_id;
                    $guests[$key]['product_name'] = $item->get_name();
                }

                if (!empty($guests)) {
                    $all_guests = array_merge($all_guests, $guests);
                }
            }
        }

        return $all_guests;
    }
