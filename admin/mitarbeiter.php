<?php
add_action('admin_menu', 'bixxs_events_addingbixxs_events_mitarbeiterFunc');

function bixxs_events_addingbixxs_events_mitarbeiterFunc()
{
    $status = bixxs_events_orderPanelAccessStatusFunc(['administrator']);
    if ($status) {
        add_submenu_page("bixxs-events", __("Mitarbeiter", BIXXS_EVENTS_TEXTDOMAIN), __('Mitarbeiter', BIXXS_EVENTS_TEXTDOMAIN), 'administrator', 'bixxs-events-mitarbeiter', 'bixxs_events_mitarbeiterFunc');
    }
}

function bixxs_events_mitarbeiterFunc()
{
    $employees = get_users(array('role__in' => array('bixxs_event_employee')));

    if (isset($_GET['page']) && $_GET['page'] == 'bixxs-events-mitarbeiter' && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user'])) {
        $user_id = $_GET['user'];

        global $wpdb;

        // Delete User metadata
        $wpdb->delete($wpdb->prefix . 'usermeta', ['user_id' => $user_id], ['%d']);

        // Delete User
        $deleted = $wpdb->delete($wpdb->prefix . 'users', ['ID' => $user_id], ['%d']);

        if ($deleted) {
            wp_redirect('admin.php?page=bixxs-events-mitarbeiter');
            exit;
        } else {
            echo "<p class='wrap'> There is a problem while deleting the user.</p>";
        }
    }

    require_once __DIR__ . '/views/mitarbeiter/user_list.php';
}


// After Employee Creation
add_action('user_register', 'bixxs_events_after_employee_registration_save', 10, 1);

function bixxs_events_after_employee_registration_save($user_id)
{
    if (isset($_POST['role']) && $_POST['role'] == 'bixxs_event_employee') {
        wp_redirect('admin.php?page=bixxs-events-mitarbeiter');
        exit;
    }
}
