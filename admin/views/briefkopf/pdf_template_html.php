<?php
require_once __DIR__ . '/pdf_fields_options.php';
?>

<style>
    body,
    div.pdf-template {
        box-sizing: border-box;
        height: 100%;
        font-size: 12px;
        font-family: "DejaVu Sans";
    }

    .clear-fix {
        clear: both;
    }

    .header {
        padding-bottom: 20px;
    }

    .logo {
        width: 70%;
        float: left;
    }

    .header-right {
        position: absolute;
        margin-left: 500px;
        margin-top: 90px;
    }

    .header-address {
        border-bottom: 1px dashed #333333;
        padding-bottom: 20px;
        padding-top: 90px;
    }

    .header-address b {
        text-decoration: underline;
        font-size: 10px;
    }

    div.ticket-body {
        width: <?php echo $img_width; ?>px;
        height: <?php echo $img_height; ?>px;
        position: relative;
        margin-top: 20px;
    }

    .ticket-body b,
    .ticket-body span {
        display: inline-block;
    }

    .ticket-body b {
        font-size: 14px;
    }

    .ticket-body img.ticket_bg {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
    }

    /* Footer Starts */
    .footer {
        margin-top: 40px;
        position: absolute;
        bottom: 70px;
    }

    .footer-box {
        padding: 20px 0px;
        width: 32%;
        float: left;
        margin-right: 10px;
        font-size: 9px;
    }
</style>
<div class="pdf-template">
    <div class="header">
        <div class="logo">
            <?php
            // Get the image and convert into string
            $img = file_get_contents(
                $this->ticketmaster_options['general_settings']['logo']
            );

            // Encode the image string data into base64
            $data = base64_encode($img);
            ?>
            <img src="data:image/png;base64, <?php echo $data; ?>" alt="" width="120">

        </div>
        <div class="header-right">
            <b>Bestellung:</b> <?php echo $order_date; ?><br>
            <b>Code-ID:</b> <?php
                            if ($quantity > 1) {

                                echo $ticket_id . $_POST['mlx_generate_events_pdf_template'];
                            } else {
                                echo $ticket_id;
                            } ?>
            <br>
            <b>Name:</b> <?php
                            if ($quantity > 1) {
                                echo $_POST['guest_name'][$_POST['mlx_generate_events_pdf_template']];
                            } else {
                                echo $_POST['guest_name'][1];
                            }

                            ?><br>
        </div>
        <div class="clear-fix"></div>
    </div>

    <div class="header-address">
        <p><b><?php echo $this->ticketmaster_options['general_settings']['heading']; ?></b></p>
        <span> <?php echo '' . $billfirstname . ' ' . $bill_lastname; ?></span><br>

        <?php if (!empty($bill_com)) { ?>
            <span><?php echo '' . $bill_com ?></span><br>
        <?php } ?>

        <span><?php echo $bill_l1 ?></span><br>

        <?php if (!empty($bill_l2)) { ?>
            <span><?php echo $bill_l2 ?> </span><br>
        <?php } ?>

        <span><?php echo '' . $bill_postcode ?> </span> <span><?php echo $bill_city ?> </span><br><br>
        <span><?php echo $this->ticketmaster_options['general_settings']['info'] ?> </span>
    </div>

    <div class="ticket-body">
        <?php if ($show_pdf_productname) { ?>
            <b style="position:absolute; top:<?php echo $produktname_top; ?>px; left: <?php echo $produktname_left; ?>px; color: <?php echo $productname_color; ?>">
                <!--Name:--> <?php echo $ticket_name; ?>
            </b>
        <?php } ?>

        <?php if ($show_pdf_order_date) { ?>
            <span style="position:absolute; top:<?php echo $order_date_top; ?>px; left: <?php echo $order_date_left; ?>px;color:<?php echo $order_date_color; ?>">Bestelldatum: <?php echo $order_date; ?></span>
        <?php } ?>

        <?php if ($show_pdf_price) { ?>
            <span style="position:absolute; top:<?php echo $price_top; ?>px; left:<?php echo $price_left; ?>px;color:<?php echo $price_color; ?>">Preis: <?php

                                                                                                                                                            if (isset($_POST['guest_price'])) {
                                                                                                                                                                if ($quantity > 1) {
                                                                                                                                                                    echo $_POST['guest_price'][$_POST['mlx_generate_events_pdf_template']];
                                                                                                                                                                } else {
                                                                                                                                                                    echo $_POST['guest_price'][1];
                                                                                                                                                                }
                                                                                                                                                            } else {
                                                                                                                                                                echo $ticket_price;
                                                                                                                                                            }
                                                                                                                                                            ?> &#8364;</span>
        <?php } ?>

        <?php if ($show_pdf_menge) { ?>
            <span style="position:absolute; top:<?php echo $menge_top; ?>px; left:<?php echo $menge_left; ?>px; color:<?php echo $menge_color; ?>">Menge: 1</span>
        <?php } ?>

        <?php if ($ticket_template_id != 0) {
            if ($show_pdf_veranstalter) { ?>
                <span style="position:absolute;top:<?php echo $veranstallter_top; ?>px;left: <?php echo $veranstallter_left; ?>px; color:<?php echo $veranstallter_color; ?>">Veranstalter: <?php echo $veranstalter; ?></span>
            <?php } ?>

            <?php if ($show_pdf_ort_veranstalter) { ?>
                <span style="position:absolute; top:<?php echo $veranstallter_ort_top; ?>px; left:<?php echo $veranstallter_ort_left; ?>px; color:<?php echo $veranstallter_ort_color; ?>">Ort der Veranstaltung: <?php echo $ort_veranstaltung; ?></span>
            <?php } ?>

            <?php if ($show_pdf_reserve_time) { ?>
                <span style="position: absolute; top:<?php echo $termien_top; ?>px; left:<?php echo $termien_left; ?>px; color:<?php echo $termien_color; ?>">Termin der Reservierung: <?php echo $bixxs_events_reserve_time; ?> </span>
        <?php }

            if ($show_pdf_ticketnumber) {
                echo "<span style='position: absolute;top:" . $ticket_number_top . "px;left:" .  $ticket_number_left . "px;color:" . $ticket_number_color . "' >Ticketnummer:";

                if ($quantity > 1) {
                    echo $ticket_id . $_POST['mlx_generate_events_pdf_template'];
                } else {
                    echo $ticket_id;
                }
                echo '</span>';
            }
        } else {
            if ($show_pdf_ticketnumber) {
                echo '<span style="position: absolute;top:' . $ticket_number_top . 'px;left: ' . $ticket_number_left . 'px;color:' . $ticket_number_color . '">Gutscheinnummer: ';
                if ($quantity > 1) {
                    echo $ticket_id . $_POST['mlx_generate_events_pdf_template'];
                } else {
                    echo $ticket_id;
                }
                echo '</span>';
            }
        }

        ?>
        <img src='<?php echo $qr_code_url;
                    ?>' alt='' style='display:inline-block;position:absolute; top:<?php echo $qrcode_top;
                                                                                    ?>px;left:<?php echo $qrcode_left;
                                                                                                ?>px;' width="100" height="100">

        <?php

        // Get the image and convert into string
        $img = file_get_contents($ticket_img);

        // Encode the image string data into base64
        $data = base64_encode($img);
        ?>

        <img src="data:image/png;base64, <?php echo $data; ?>" alt="" class="ticket_bg">

    </div>

    <div class="additional-info">
        <p><b>Zusammenfassung:</b>
            <span><?php echo $this->ticketmaster_options['general_settings']['additional_info']; ?></span>
        </p>

        <p><b>Auswahl:</b><br>
            <span><?php echo  $bixxs_events_addons; ?></span>
        </p>

        <b>Zahlungsmethode: </b> <?php echo $paymethod; ?> <br>
        <b>Versandart: </b> <?php echo $shipping_method; ?> <br>

        <b>Ihr Hinweis: </b><?php echo $customer_note; ?>
    </div>


    <div class="footer">
        <div class="footer-box">
            <small><?php echo nl2br($this->ticketmaster_options['general_settings']['footer'][1]); ?></small>
        </div>
        <div class="footer-box">
            <small><?php echo nl2br($this->ticketmaster_options['general_settings']['footer'][2]); ?></small>
        </div>
        <div class="footer-box">
            <small><?php echo nl2br($this->ticketmaster_options['general_settings']['footer'][3]); ?></small>
        </div>
    </div>
</div>