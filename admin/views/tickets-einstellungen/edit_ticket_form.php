<div class="wrap">
    <form method="POST">
        <h1 class="wp-heading-inline">Veranstaltungs Einstellungen</h1>
        <hr><br>

        <?php if (!empty($this->notice)) { ?>
            <div class="notice is-dismissible notice-success">
                <p><?php echo $this->notice; ?> </p>
            </div>
        <?php } ?>

        <a href="admin.php?page=bixxs-events-tickeseinstellungen&action=bixxs_events_new_template" class="page-title-action">+ Neue Veranstaltung</a>
        <a href="admin.php?page=bixxs-events-tickeseinstellungen" class="page-title-action"> Alle anzeigen </a>

        <h2>Veranstaltung bearbeiten:</h2>

        <table class="form-table">
            <tr>
                <th>
                    <label for="ticketnname">Name</label>
                </th>
                <td>
                    <input type="text" name="ticketnname" id="ticketnname" class="regular-text" value="<?php echo $this->set_ticketnname; ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="veranstalter">Veransteller</label>
                </th>
                <td>
                    <input type="text" name="veranstalter" id="veranstalter" class="regular-text" value="<?php echo $this->set_veranstalter; ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ort_veranstaltung">Ort der Veranstaltung</label>
                </th>
                <td>
                    <input type="text" name="ort_veranstaltung" id="ort_veranstaltung" class="regular-text" value="<?php echo $this->set_ort_veranstaltung; ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="height">H&#246;he</label>
                </th>
                <td>
                    <input type="text" name="height" id="height" class="regular-text" value="<?php echo $this->set_height; ?>"> <span>352 Pixel und 75 DPI</span>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="width">Breite</label>
                </th>
                <td>
                    <input type="text" name="width" id="width" class="regular-text" value="<?php echo $this->set_width; ?>"> <span>700 Pixel und 75 DPI</span>
                </td>
            </tr>

            <hr>

            <tr>
                <th>
                    <p>Produktname:</p>
                </th>
                <td>
                    <p>
                        <label for="produktname_left">Position von links:</label>
                        <input type="text" name="produktname_left" id="produktname_left" size="15" value="<?php echo $this->set_produktname_left; ?>">
                    </p>

                    <p>
                        <label for="produktname_top">Position von oben:</label>
                        <input type="text" name="produktname_top" id="produktname_top" size="15" value="<?php echo $this->set_produktname_top; ?>">
                    </p>

                    <p>
                        <label for="produktname_color">Produktname Farbe:</label>
                        <input type="text" name="produktname_color" id="produktname_color" size="15" value="<?php echo $this->set_produktname_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Bestelldatum:</p>
                </th>
                <td>
                    <p>
                        <label for="order_date_left">Position von links:</label>
                        <input type="text" name="order_date_left" id="order_date_left" size="15" value="<?php echo $this->set_order_date_left; ?>">
                    </p>

                    <p>
                        <label for="order_date_top">Position von oben:</label>
                        <input type="text" name="order_date_top" id="order_date_top" size="15" value="<?php echo $this->set_order_date_top; ?>">
                    </p>


                    <p>
                        <label for="order_date_color">Bestelldatum Farbe:</label>
                        <input type="text" name="order_date_color" id="order_date_color" size="15" value="<?php echo $this->set_order_date_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Preis:</p>
                </th>
                <td>
                    <p>
                        <label for="price_left">Position von links:</label>
                        <input type="text" name="price_left" id="price_left" size="15" value="<?php echo $this->set_price_left; ?>">
                    </p>

                    <p>
                        <label for="price_top">Position von oben:</label>
                        <input type="text" name="price_top" id="price_top" size="15" value="<?php echo $this->set_price_top; ?>">
                    </p>


                    <p>
                        <label for="price_color">Preis Farbe:</label>
                        <input type="text" name="price_color" id="price_color" size="15" value="<?php echo $this->set_price_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Termin der Reservierung:</p>
                </th>
                <td>
                    <p>
                        <label for="termien_left">Position von links:</label>
                        <input type="text" name="termien_left" id="termien_left" size="15" value="<?php echo $this->set_termien_left; ?>">
                    </p>


                    <p>
                        <label for="termien_top">Position von oben:</label>
                        <input type="text" name="termien_top" id="termien_top" size="15" value="<?php echo $this->set_termien_top; ?>">
                    </p>

                    <p>
                        <label for="termien_color">Reservierung Farbe:</label>
                        <input type="text" name="termien_color" id="termien_color" size="15" value="<?php echo $this->set_termien_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Veranstalter:</p>
                </th>
                <td>
                    <p>
                        <label for="veranstallter_left">Position von links:</label>
                        <input type="text" name="veranstallter_left" id="veranstallter_left" size="15" value="<?php echo $this->set_veranstallter_left; ?>">
                    </p>


                    <p>
                        <label for="veranstallter_top">Position von oben:</label>
                        <input type="text" name="veranstallter_top" id="veranstallter_top" size="15" value="<?php echo $this->set_veranstallter_top; ?>">
                    </p>


                    <p>
                        <label for="veranstallter_color">Veranstalter Farbe:</label>
                        <input type="text" name="veranstallter_color" id="veranstallter_color" size="15" value="<?php echo $this->set_veranstallter_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Ort der Veranstaltung :</p>
                </th>
                <td>
                    <p>
                        <label for="veranstallter_ort_left">Position von links:</label>
                        <input type="text" name="veranstallter_ort_left" id="veranstallter_ort_left" size="15" value="<?php echo $this->set_veranstallter_ort_left; ?>">
                    </p>

                    <p>
                        <label for="veranstallter_ort_top">Position von oben:</label>
                        <input type="text" name="veranstallter_ort_top" id="veranstallter_ort_top" size="15" value="<?php echo $this->set_veranstallter_ort_top; ?>">
                    </p>


                    <p>
                        <label for="veranstallter_ort_color">Veranstaltungs Ort Farbe:</label>
                        <input type="text" name="veranstallter_ort_color" id="veranstallter_ort_color" size="15" value="<?php echo $this->set_veranstallter_ort_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Menge:</p>
                </th>
                <td>
                    <p>
                        <label for="menge_left">Position von links:</label>
                        <input type="text" name="menge_left" id="menge_left" size="15" value="<?php echo $this->set_menge_left; ?>">
                    </p>

                    <p>
                        <label for="menge_top">Position von oben:</label>
                        <input type="text" name="menge_top" id="menge_top" size="15" value="<?php echo $this->set_menge_top; ?>">
                    </p>


                    <p>
                        <label for="menge_color">Menge Farbe:</label>
                        <input type="text" name="menge_color" id="menge_color" size="15" value="<?php echo $this->set_menge_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <p>Ticketnummer:</p>
                </th>
                <td>
                    <p>
                        <label for="ticket_number_left">Position von links:</label>
                        <input type="text" name="ticket_number_left" id="ticket_number_left" size="15" value="<?php echo $this->set_ticket_number_left; ?>">
                    </p>

                    <p>
                        <label for="ticket_number_top">Position von oben:</label>
                        <input type="text" name="ticket_number_top" id="ticket_number_top" size="15" value="<?php echo $this->set_ticket_number_top; ?>">
                    </p>


                    <p>
                        <label for="ticket_number_color">Ticketnummer Farbe:</label>
                        <input type="text" name="ticket_number_color" id="ticket_number_color" size="15" value="<?php echo $this->set_ticket_number_color; ?>">
                    </p>
                </td>
            </tr>


            <tr>
                <th>
                    <p>QR Code:</p>
                </th>
                <td>
                    <p>
                        <label for="qrcode_left">Position von links:</label>
                        <input type="text" name="qrcode_left" id="qrcode_left" size="15" value="<?php echo $this->set_qrcode_left; ?>">
                    </p>

                    <p>
                        <label for="qrcode_top">Position von oben:</label>
                        <input type="text" name="qrcode_top" id="qrcode_top" size="15" value="<?php echo $this->set_qrcode_top; ?>">
                    </p>

                    <p>
                        <label for="qrcode_color">QR Code Farbe:</label>
                        <input type="text" name="qrcode_color" id="qrcode_color" size="15" value="<?php echo $this->set_qrcode_color; ?>">
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="ticketimage">Ticket Bild:</label></th>
                <td>
                    <input type="button" value="Upload Image" class="js-image-upload button button-secondary"> <br>

                    <img src="<?php echo $this->set_ticketimage; ?>" alt="" class="uploaded-logo" width="200">

                    <input type="hidden" name="ticketimage" id="ticketimage" class="image-link-input regular-text" value="<?php echo $this->set_ticketimage; ?>">
                </td>
            </tr>

            <tr>
                <th>
                    <p>
                        <input type="submit" value="Ticket aktualisieren" name="bixxs_events_update_template" class="button button-primary">
                    </p>

                    <p>
                        <input type="submit" value="Vorschauvorlage" name="bixxs_events_show_demo_pdf_template" formtarget="_blank" class="button button-primary">
                    </p>
                </th>
            </tr>
        </table>
    </form>
</div>