<div class="wrap">
    <form method="POST">
        <h1 class="wp-heading-inline">Veranstaltungen Einstellungen</h1>
        <hr><br>

        <?php if( !empty( $this->notice )){ ?>
            <div class="notice is-dismissible notice-success">
                <p><?php echo $this->notice; ?> </p>
            </div>
        <?php } ?>

        <a href="admin.php?page=bixxs-events-tickeseinstellungen">Alle ansehen</a>

        <h2>Neue Veranstaltungsvorlage:</h2>

        <table class="form-table">
            <tr>
                <th>
                    <label for="ticketnname">Name:</label>
                </th>
                <td>
                    <input type="text" name="ticketnname" id="ticketnname" class="regular-text" value="" required>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="veranstalter">Veransteller:</label>
                </th>
                <td>
                    <input type="text" name="veranstalter" id="veranstalter" class="regular-text" value="">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="ort_veranstaltung">Ort der Veranstaltung:</label>
                </th>
                <td>
                    <input type="text" name="ort_veranstaltung" id="ort_veranstaltung" class="regular-text" value="">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="height">H&#246;he:</label>
                </th>
                <td>
                    <input type="text" name="height" id="height" class="regular-text" value=""> <span>Bild h&#246;he 352 Pixel und 75 DPI Aufl&#246;sung</span>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="width">Breite:</label>
                </th>
                <td>
                    <input type="text" name="width" id="width" class="regular-text" value=""> <span>Bild breite 700 Pixel und 75 DPI Aufl&#246;sung</span>
                </td>
            </tr>

            <tr>
                <th><p>Produktname:</p></th>
                <td>
                    <p>    
                        <label for="produktname_left">Position von links:</label>
                        <input type="text" name="produktname_left" id="produktname_left" value="" size="15">
                    </p>

                    <p>
                        <label for="produktname_top">Position von oben:</label>
                        <input type="text" name="produktname_top" id="produktname_top" value="" size="15">
                    </p>

                   <p>
                        <label for="produktname_color">Text Farbe:</label>
                        <input type="text" name="produktname_color" id="produktname_color"  value="" size="15">
                   </p>
                </td>
            </tr>
            <tr>
                <th><p>Bestelldatum:</p></th>
                <td>
                    <p>
                        <label for="order_date_left">Position von links:</label>
                        <input type="text" name="order_date_left" id="order_date_left" size="15" value=""> 
                    </p>   
                   <p>
                        <label for="order_date_top">Position von oben:</label>
                        <input type="text" name="order_date_top" id="order_date_top" size="15" value="">
                   </p>
                    <p>
                        <label for="order_date_color">Text Farbe:</label>
                        <input type="text" name="order_date_color" id="order_date_color" size="15" value="">
                    </p>
                </td>
            </tr>
            <tr>
                <th><p>Preis:</p></th>
                <td>
                    <p>
                    <label for="price_left">Position von links:</label>
                    <input type="text" name="price_left" id="price_left" size="15" value="">
                    </p>

                    <p>
                    <label for="price_top">Position von oben:</label>
                    <input type="text" name="price_top" id="price_top" size="15" value="">
                    </p>

                    <p>
                    <label for="price_color">Text Farbe:</label>
                    <input type="text" name="price_color" id="price_color" size="15" value="">
                    </p>
                </td>
            </tr>
            <tr>
                <th><p>Termin der Reservierung:</p></th>
                <td>
                    <p>
                    <label for="termien_left">Position von links:</label>
                    <input type="text" name="termien_left" id="termien_left" size="15" value=""> 
                    </p>
                    
                    <p>
                    <label for="termien_top">Position von oben:</label>
                    <input type="text" name="termien_top" id="termien_top" size="15" value="">
                    </p>

                    <p>
                    <label for="termien_color">Text Farbe:</label>
                    <input type="text" name="termien_color" id="termien_color" size="15" value="">
                    </p>
                </td>
            </tr>
            <tr>
                <th><p>Veranstalter:</p></th>
                <td>
                    <p><label for="veranstallter_left">Position von links:</label>
                    <input type="text" name="veranstallter_left" id="veranstallter_left" size="15" value="">
                    </p>

                    <p>
                    <label for="veranstallter_top">Position von oben:</label>
                    <input type="text" name="veranstallter_top" id="veranstallter_top" size="15" value="">
                    </p>

                    <p>
                    <label for="veranstallter_color">Text Farbe:</label>
                    <input type="text" name="veranstallter_color" id="veranstallter_color" size="15" value="">
                    </p>
                </td>
            </tr>
            <tr>
                <th><p>Ort der Veranstaltung :</p></th>
                <td>
                    <p>
                    <label for="veranstallter_ort_left">Position von links:</label>
                    <input type="text" name="veranstallter_ort_left" id="veranstallter_ort_left" size="15" value=""> 
                    </p>

                    <p>
                    <label for="veranstallter_ort_top">Position von oben:</label>
                    <input type="text" name="veranstallter_ort_top" id="veranstallter_ort_top" size="15" value="">
                    </p>

                    <p>
                    <label for="veranstallter_ort_color">Text Farbe:</label>
                    <input type="text" name="veranstallter_ort_color" id="veranstallter_ort_color" size="15" value="">
                    </p>
                </td>
            </tr>
            <tr>
                <th><p>Menge:</p></th>
                <td>
                   <p>
                   <label for="menge_left">Position von links:</label>
                    <input type="text" name="menge_left" id="menge_left" size="15" value="">
                   </p>
                    <p>
                    <label for="menge_top">Position von oben:</label>
                    <input type="text" name="menge_top" id="menge_top" size="15" value="">
                    </p>
                    <p>
                    <label for="menge_color">Text Farbe:</label>
                    <input type="text" name="menge_color" id="menge_color" size="15" value="">
                    </p>
                </td>
            </tr>

            <tr>
                <th><p>Ticketnummer:</p></th>
                <td>
                   <p>
                   <label for="ticket_number_left">Position von links:</label>
                    <input type="text" name="ticket_number_left" id="ticket_number_left" size="15" value="">
                   </p>
                    <p>
                    <label for="ticket_number_top">Position von oben:</label>
                    <input type="text" name="ticket_number_top" id="ticket_number_top" size="15" value="">
                    </p>
                    <p>
                    <label for="ticket_number_color">Text Farbe:</label>
                    <input type="text" name="ticket_number_color" id="ticket_number_color" size="15" value="">
                    </p>
                </td>
            </tr>
            
            <tr>
                <th><p>QR Code:</p></th>
                <td>
                    <p>
                    <label for="qrcode_left">Position von links:</label>
                    <input type="text" name="qrcode_left" id="qrcode_left" size="15" value="">
                    </p>
                    <p>
                    <label for="qrcode_top">Position von oben:</label>
                    <input type="text" name="qrcode_top" id="qrcode_top" size="15" value="">
                    </p>

                    <p>
                    <label for="qrcode_color">QR Code Farbe:</label>
                    <input type="text" name="qrcode_color" id="qrcode_color" size="15" value="">
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="ticketimage">Veranstaltungs Bild:</label></th>
                <td>
                    <input type="button" value="Upload Image" class="js-image-upload button button-secondary"> <br>
                    
                    <img src="" alt="" class="uploaded-logo" width="200">
                    
                    <input type="hidden" name="ticketimage" id="ticketimage" class="image-link-input regular-text" value="">
                </td>
            </tr>

            <tr>
                <th>
                    <input type="submit" value="Absenden" name="bixxs_events_save_template" class="button button-primary">
                </th>
            </tr>
        </table>
    </form>
</div>