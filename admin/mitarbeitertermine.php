<?php
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
        <label for="data0">Datum</label> <input id="data0" name="mitarbeitertermine_date" required="" type="date" value="<?php echo (isset($_POST['mitarbeitertermine_date']) ? $_POST['mitarbeitertermine_date'] : ''); ?>" />
        <input type="submit" value="Anzeigen">&nbsp;
        <input type="submit" name="bixxs_events_pdf" formtarget="_blank" value="PDF export">
    </form>

    <table class="wp-list-table widefat fixed striped table-view-list mt20">
        <thead>
            <tr>
                <td>Ticketnummer</td>
                <td>Name / Vorname</td>
                <td>Produkt</td>
            </tr>
        </thead>
        <tbody>
        <?php

        global $wpdb;
        $employee_id = get_current_user_id();

        $order_ids = bixxs_events_get_orders_by_employee($employee_id);

        foreach ($order_ids as $id) {
            $order = wc_get_order($id);

            foreach ($order->get_items() as $item) {
                $guests = (array) json_decode($item->get_meta('_mlx_guests'));

                if (!empty($guests)) {
                    foreach ($guests as $key => $guest) {

                        $ticket_number = (count($guests) > 1) ? $item->get_id() . $key : $item->get_id();

                        echo '<tr>';

                        echo '<td>';
                        echo $ticket_number;
                        echo '</td>';

                        echo '<td>';
                        echo $guest->first_name . ' ' . $guest->last_name;
                        echo '</td>';

                        echo '<td>';
                        echo $item->get_data()['name'];
                        echo '</td>';

                        echo '</tr>';
                    }
                }
            }
        }
    }
