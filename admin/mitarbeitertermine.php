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

    <form action="" method="GET" class="d-flex" id="employee_tickets_filter">
        <select name="tickets_filter_by" id="tickets_filter_by">
            <option value="all">Alle Tickets</option>
            <option value="date">Nach Datum filtern</option>
        </select>

        <div class="filter_by_date">
            <label for="filter_by_date">Mitarbeiter auswählen</label> <input id="filter_by_date" name="filter_by_date" type="date" value="<?php echo (isset($_REQUEST['filter_by_date']) ? $_REQUEST['filter_by_date'] : ''); ?>" />
        </div>

        <?php if (current_user_can('manage_options')) {
            $employees = get_users(array('role__in' => array('bixxs_event_employee')));
        ?>
            <div>
                <label for="select_employee_filter">Wählen Sie Mitarbeiter</label>
                <select name="select_employee_filter" id="select_employee_filter">
                    <option value="0">Alle</option>
                    <?php
                    foreach ($employees as $key => $employee) {
                        echo "<option value='{$employee->data->ID}'>{$employee->data->display_name}</option>";
                    }
                    ?>
                </select>
            </div>
        <?php } ?>

        <input type="submit" name="bixxs_events_employee_tickets_filter" value="Filtern">&nbsp;

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
        <tbody class="employee_tickets_data">
            <?php echo bixxs_events_show_employee_tickets(); ?>
        </tbody>
    </table>
<?php
}

/**
 * Filter Employee Tickets
 */
add_action('wp_ajax_filter_employee_tickets', 'bixxs_events_filter_employee_tickets');
add_action('wp_ajax_nopriv_filter_employee_tickets', 'bixxs_events_filter_employee_tickets');
function bixxs_events_filter_employee_tickets()
{
    $employee_guests = bixxs_events_show_employee_tickets();
    wp_send_json($employee_guests);
}

// Get all employee guests
function bixxs_events_get_all_employee_guests()
{
    if (current_user_can('bixxs_event_employee')) {
        $employee_id = get_current_user_id();
    } else {
        $employee_id = isset($_REQUEST['select_employee_filter']) ? $_REQUEST['select_employee_filter'] : 0;
    }

    $filter_date = isset($_REQUEST['filter_by_date']) ? $_REQUEST['filter_by_date'] : '';

    $order_ids = bixxs_events_get_orders_by_employee($employee_id, $filter_date);

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

function bixxs_events_show_employee_tickets()
{
    $all_guests =  bixxs_events_get_all_employee_guests();

    $html = '';

    if (empty($all_guests)) {
        $html .=  "<tr><td>Keine Tickets gefunden</td></tr>";
    } else {
        foreach ($all_guests as $key => $guest) {
            $html .=  '<tr>';
            $html .=  '<td>';
            $html .=  $guest['ticket_id'];
            $html .=  '</td>';

            $html .=  '<td>';
            $html .=  $guest['first_name'] . ' ' . $guest['last_name'];
            $html .=  '</td>';

            $html .=  '<td>';
            $html .=  $guest['street'];
            $html .=  '</td>';

            $html .=  '<td>';
            $html .=  $guest['zip'] . ' ' . $guest['city'];
            $html .=  '</td>';

            $html .=  '<td>';
            $html .=  $guest['telephone'];
            $html .=  '</td>';

            $html .=  '<td>';
            $html .=  $guest['email'];
            $html .=  '</td>';

            $html .=  '<td>';
            $html .=  $guest['product_name'];
            $html .=  '</td>';
            $html .=  '</tr>';
        }
    }

    return $html;
}

function bixxs_events_employee_export_pdf()
{
    if (isset($_REQUEST['bixxs_events_employee_tickets_pdf'])) {
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
add_action('init', 'bixxs_events_employee_export_pdf');
