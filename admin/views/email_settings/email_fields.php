<div class="wrap">

    <h1>Email Einstellung</h1>
    <hr>

    <h2>Downloadbestätigung , die E.Mail geht an den Kunden</h2>
    <form action="" method="post">
        <input type="checkbox" name="email_settings[download_ticket][active]" <?php echo isset($mlx_email_options['download_ticket']['active']) && $mlx_email_options['download_ticket']['active'] ? 'checked' : ''; ?>>
        <label for="email_settings[download_ticket][active]">E-Mail senden</label>

        <label for="email_settings[download_ticket][subject]">Betreff</label>
        <input type="text" name="email_settings[download_ticket][subject]" value="<?php echo isset($mlx_email_options['download_ticket']['subject']) ? $mlx_email_options['download_ticket']['subject'] : 'Downloadbestätigung'; ?>" size="50">

        <?php
        $download_content = isset($mlx_email_options['download_ticket']['body']) ? $mlx_email_options['download_ticket']['body'] : $email_body_download;

        $download_editor_id = "email_settings_download_ticket_body";
        $args = array(
            'media_buttons' => false,
            'textarea_name' => "email_settings_download_ticket_body",
            'textarea_rows' => get_option('default_post_edit_rows', 10),
        );
        wp_editor(wpautop($download_content), $download_editor_id, $args);
        ?>
        <br>
        <hr>

        <h2>Terminbestätigung , die E.Mail geht an den Kunden und den Admin</h2>
        <input type="checkbox" name="email_settings[buy_ticket][active]" <?php echo $mlx_email_options['buy_ticket']['active'] ? 'checked' : ''; ?>>
        <label for="email_settings[buy_ticket][active]">E-Mail senden</label>

        <label for="email_settings[buy_ticket][subject]">Betreff</label>
        <input type="text" name="email_settings[buy_ticket][subject]" value="<?php echo $mlx_email_options['buy_ticket']['subject']; ?>"><br>

        <?php
        $buy_content = isset($mlx_email_options['buy_ticket']['body']) ? $mlx_email_options['buy_ticket']['body'] : $email_body;

        $buy_editor_id = "email_settings_buy_ticket_body";
        $args = array(
            'media_buttons' => false,
            'textarea_name' => "email_settings_buy_ticket_body",
            'textarea_rows' => get_option('default_post_edit_rows', 10),
        );
        wp_editor(wpautop($buy_content), $buy_editor_id, $args);
        ?>

        <br><br>
        <hr>
        <h2>Termin Umbuchung , die E.Mail geht an den Kunden und den Admin</h2>
        <input type="checkbox" name="email_settings[rebook_ticket][active]" <?php echo $mlx_email_options['rebook_ticket']['active'] ? 'checked' : ''; ?>>
        <label for="email_settings[rebook_ticket][active]">E-Mail senden</label>
        <label for="email_settings[rebook_ticket][subject]">Betreff</label>
        <input type="text" name="email_settings[rebook_ticket][subject]" value="<?php echo $mlx_email_options['buy_ticket']['subject']; ?>">

        <?php

        $reebok_content = isset($mlx_email_options['rebook_ticket']['body']) ? $mlx_email_options['rebook_ticket']['body'] : $email_body_reebok;

        $reebok_editor_id = "email_settings_rebook_ticket_body";
        $args = array(
            'media_buttons' => false,
            'textarea_name' => "email_settings_rebook_ticket_body",
            'textarea_rows' => get_option('default_post_edit_rows', 10),
        );
        wp_editor(wpautop($reebok_content), $reebok_editor_id, $args);
        ?>

        <br><br>
        <input type="submit" value="Speichern" name="save_email_settings" class="button-primary">
    </form>
</div>