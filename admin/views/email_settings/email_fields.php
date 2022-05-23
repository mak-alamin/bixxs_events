<div class="wrap">

    <h1>Email Einstellung</h1>
    <hr>

    <h2>Downloadbestätigung , die E.Mail geht an den Kunden</h2>
    <form action="" method="post">
        <input type="checkbox" name="email_settings[download_ticket][active]" <?php echo isset($mlx_email_options['download_ticket']['active']) && $mlx_email_options['download_ticket']['active'] ? 'checked' : ''; ?>>
        <label for="email_settings[download_ticket][active]">E-Mail senden</label>

        <label for="email_settings[download_ticket][subject]">Betreff</label>
        <input type="text" name="email_settings[download_ticket][subject]" value="<?php echo isset($mlx_email_options['download_ticket']['subject']) ? $mlx_email_options['download_ticket']['subject'] : 'Downloadbestätigung'; ?>" size="50">
        <br>
        <textarea name="email_settings[download_ticket][body]" id="" cols="62" rows="12">
        <?php echo isset($mlx_email_options['download_ticket']['body']) ? $mlx_email_options['download_ticket']['body'] : $email_body_download; ?>
        </textarea>

        <hr>

        <h2>Terminbestätigung , die E.Mail geht an den Kunden und den Admin</h2>
        <input type="checkbox" name="email_settings[buy_ticket][active]" <?php echo $mlx_email_options['buy_ticket']['active'] ? 'checked' : ''; ?>>
        <label for="email_settings[buy_ticket][active]">E-Mail senden</label>

        <label for="email_settings[buy_ticket][subject]">Betreff</label>
        <input type="text" name="email_settings[buy_ticket][subject]" value="<?php echo $mlx_email_options['buy_ticket']['subject']; ?>"><br>

        <textarea name="email_settings[buy_ticket][body]" id="" cols="62" rows="12">
            <?php echo $mlx_email_options['buy_ticket']['body']; ?>
        </textarea>


        <br><br><br>
        <h2>Termin Umbuchung , die E.Mail geht an den Kunden und den Admin</h2>
        <input type="checkbox" name="email_settings[rebook_ticket][active]" <?php echo $mlx_email_options['rebook_ticket']['active'] ? 'checked' : ''; ?>>
        <label for="email_settings[rebook_ticket][active]">E-Mail senden</label>
        <label for="email_settings[rebook_ticket][subject]">Betreff</label>
        <input type="text" name="email_settings[rebook_ticket][subject]" value="<?php echo $mlx_email_options['buy_ticket']['subject']; ?>"><br>
        <textarea name="email_settings[rebook_ticket][body]" id="" cols="62" rows="12">
        <?php echo $mlx_email_options['rebook_ticket']['body']; ?>
        </textarea>

        <br><br>
        <input type="submit" value="Speichern" name="save_email_settings" class="button-primary">
    </form>
</div>