<?php

use Dompdf\Dompdf;
use Dompdf\Options;

add_action('admin_menu', 'bixxs_events_addingbixxs_events_guestlistFunc');

function bixxs_events_addingbixxs_events_guestlistFunc()
{
    $status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
    if ($status) {
        add_submenu_page("bixxs-events", __("Gästeliste", BIXXS_EVENTS_TEXTDOMAIN), __('Gästeliste', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-guestlist', 'bixxs_events_guestlistFunc');
    }
}

function bixxs_events_guestlistFunc()
{

    $all_guests = array();

?>


    <h1>Gästeliste</h1>

    <form action="" method="post">
        <label for="data0">Datum</label> <input id="data0" name="guestlist_date" required="" type="date" value="<?php echo (isset($_POST['guestlist_date']) ? $_POST['guestlist_date'] : ''); ?>" />
        <input type="submit" value="Anzeigen">
        <input type="submit" name="bixxs_events_csv" value="CSV export">
        <input type="submit" name="bixxs_events_pdf" formtarget="_blank" value="PDF export">
    </form>

    <?php

    if (isset($_POST['guestlist_date'])) {
        $all_guests = bixxs_events_get_guests($_POST['guestlist_date']);
    ?>
        <table border="0" width="90%" cellspacing="10" cellpadding="5">
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
                if ($all_guests) {
                    foreach ($all_guests as $guest) {
                        echo '<tr><td>' . $guest['ticket_id'] . '</td><td>' . $guest['first_name'] . ' ' . $guest['last_name'] .
                            '</td><td>' . $guest['street'] . '</td><td>' . $guest['zip'] . ' ' . $guest['city'] .
                            '</td><td>' . $guest['telephone'] . '</td><td>' . $guest['email'] . '</td><td>' .
                            $guest['product_name'] . '</td></tr>';
                    }
                } else {
                    echo "<tr><td>Keine Tickets gefunden</td></tr>";
                }
                ?>
            </tbody>
        </table>
<?php
    }
}

function bixxs_events_get_guests($date, $product_id = false)
{
    $search_date = date("d.m.Y", strtotime($date));
    $all_guests = array();

    $orders = wc_get_orders(array(
        'limit'        => -1,
        'orderby'      => 'date',
        'order'        => 'DESC',
        'meta_key'     => 'Reservierung Datum',
        'meta_value'   => $search_date,
        'meta_compare' => 'LIKE',
    ));

    foreach ($orders as $order) {
        $items = $order->get_items();
        $saved_meta = $order->get_meta('Reservierung Datum');

        foreach ($items as $item_id => $item) {
            if ($product_id && $item->get_product_id() != $product_id) {
                continue;
            }

            if ($item->get_meta('Reservierung Datum') == $search_date) {
                $guests = json_decode($item->get_meta('_mlx_guests'), true);
                foreach ($guests as $key => $guest) {
                    $guests[$key]['ticket_id'] = $item_id;
                    $guests[$key]['product_name'] = $item->get_name();
                }
                $all_guests = array_merge($all_guests, $guests);
            }
        }
    }

    return $all_guests;
}

function bixxs_events_export()
{
    if (!isset($_POST['guestlist_date']) || (!isset($_POST['bixxs_events_csv']) && !isset($_POST['bixxs_events_pdf'])))
        return;

    $all_guests = bixxs_events_get_guests($_POST['guestlist_date']);
    $format_date = date("d-m-Y", strtotime($_POST['guestlist_date']));
    // output headers so that the file is downloaded rather than displayed

    if (isset($_POST['bixxs_events_csv'])) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $format_date . '-gaesteliste.csv');

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // output the column headings
        fputcsv($output, array('Ticketnummer', 'Name', 'Straße', 'PLZ Ort', 'Telefon', 'E-Mail', 'Produkt'));


        // loop over the rows, outputting them
        foreach ($all_guests as $guest) {
            fputcsv($output, array($guest['ticket_id'], $guest['first_name'] . ' ' . $guest['last_name'], $guest['street'], $guest['zip'] . ' ' . $guest['city'], $guest['telephone'], $guest['email'], $guest['product_name']));
        }

        die();
    } else if (isset($_POST['bixxs_events_pdf'])) {
        require_once __DIR__ . '/views/guestlist/template-pdf.php';

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $dompdf->setPaper('A4');

        $html = bixxs_events_render_pdf($all_guests);

        $dompdf->loadHtml($html);

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream($format_date . 'gaesteliste.pdf', array('Attachment' => false));
        die();
    }
}

add_action('admin_init', 'bixxs_events_export');
