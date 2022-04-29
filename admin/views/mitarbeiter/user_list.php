<div class="wrap">
    <h1 class="wp-heading-inline">Mitarbeiter Einstellungen</h1>
    <a href="user-new.php?action=create_employee" class="page-title-action">Neuen Mitarbeiter hinzufügen</a>
    <br>
    <br>

    <table class="wp-list-table widefat fixed striped table-view-list users">
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
        </tr>

        <?php
        foreach ($employees as $key => $employee) {
            $id = $employee->data->ID;
        ?>
            <tr>
                <td class="username column-username has-row-actions column-primary"><img alt="" src="<?php echo get_avatar_url($id); ?>" class="avatar avatar-32 photo" height="32" width="32" loading="lazy"> <strong><a href=""><?php echo $employee->data->user_login; ?></a></strong><br>
                    <div class="row-actions">
                        <span class="edit"><a href="user-edit.php?user_id=<?php echo $id; ?>">Edit</a> | </span>
                        <span class="delete"><a class="submitdelete" href="<?php echo wp_nonce_url('admin.php?page=bixxs-events-mitarbeiter&action=delete&user=' . $id); ?>">Delete</a> | </span>
                    </div>
                </td>
                <td><?php echo $employee->data->display_name; ?></td>
                <td><?php echo $employee->data->user_email; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>