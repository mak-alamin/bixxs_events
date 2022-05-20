<?php
require_once BIXXS_EVENTS_PLUGIN_DIR . '/admin/views/briefkopf/pdf_fields_options.php';
?>

<style>
    /* PDF Template Style */
    body,
    div.pdf-template {
        box-sizing: border-box;
        height: 100%;
        font-size: 12px;
        font-family: "DejaVu Sans";
        page-break-inside: avoid;
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
        margin-top: 40px;
    }

    .header-address {
        border-bottom: 1px dashed #333333;
        padding-bottom: 20px;
        padding-top: 40px;
    }

    .header-address b {
        text-decoration: underline;
        font-size: 10px;
    }

    div.ticket-body {
        height: <?php echo $height; ?>px;
        width: <?php echo $width; ?>px;
        position: relative;
        margin-top: 20px;
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
            <img src="<?php echo $this->ticketmaster_general_options['logo']; ?>" alt="" width="120">
        </div>
        <div class="header-right">
            <b>Bestellung:</b> 27.12.2020<br>
            <b>Code-ID:</b> 1480<br>
            <b>Name:</b> Michael Lenuweit<br>
        </div>
        <div class="clear-fix"></div>
    </div>

    <div class="header-address">
        <p><b><?php echo $this->ticketmaster_general_options['heading']; ?></b>
        <p>

            <span>Michael Lenuweit</span><br>
            <span>Marie-Curie-Strasse 6</span><br>
            <span>47475 Kamp-Lintfort</span><br><br>
            <span><?php echo $this->ticketmaster_general_options['info']; ?> </span>
    </div>

    <div class="ticket-body">
        <?php if ($show_pdf_productname) { ?>
            <b style="position:absolute; top:<?php echo $produktname_top; ?>px; left: <?php echo $produktname_left; ?>px; color: <?php echo $produktname_color; ?>">Name: Ticket Name</b>
        <?php } ?>

        <?php if ($show_pdf_order_date) { ?>
            <span style="position:absolute; top: <?php echo $order_date_top; ?>px; left:  <?php echo $order_date_left; ?>px; color: <?php echo $order_date_color; ?> ">Bestelldatum: 28.06.2021</span>
        <?php } ?>

        <?php if ($show_pdf_price) { ?>
            <span style="position:absolute; top:<?php echo $price_top; ?>px; left: <?php echo $price_left; ?>px; color: <?php echo $price_color; ?> ">Preis: €20 </span>
        <?php } ?>

        <?php if ($show_pdf_reserve_time) { ?>
            <span style="position:absolute;top: <?php echo $termien_top; ?>px;left:<?php echo $termien_left; ?>px;color:<?php echo $termien_color; ?>" class="reservation-date">Termin der Re servierung: 31.12.2021</span>
        <?php } ?>

        <?php if ($show_pdf_veranstalter) { ?>
            <span style="position:absolute;top: <?php echo $veranstallter_top; ?>px;left:<?php echo $veranstallter_left; ?>px;color:<?php echo $veranstallter_color; ?>" class="veranstalter">Veranstalter: Veranstalter</span>
        <?php } ?>

        <?php if ($show_pdf_ort_veranstalter) { ?>
            <span style="position:absolute;top: <?php echo $veranstallter_ort_top; ?>px;left:<?php echo $veranstallter_ort_left; ?>px;color:<?php echo $veranstallter_ort_color; ?>" class="ort">Ort der Veranstaltung: Ort der Veranstaltung</span>
        <?php } ?>

        <?php if ($show_pdf_menge) { ?>
            <span style="position:absolute;top: <?php echo $menge_top; ?>px;left:<?php echo $menge_left; ?>px;color:<?php echo $menge_color; ?>">Menge: 1</span>
        <?php } ?>

        <?php if ($show_pdf_ticketnumber) { ?>
            <span style="position:absolute;top: <?php echo $ticket_number_top; ?>px;left:<?php echo $ticket_number_left; ?>px;color:<?php echo $ticket_number_color; ?>" class="ticket-number">Ticketnummer: ist gleiche wie Bestellnummer</span>
        <?php } ?>

        <img src="<?php echo BIXXS_EVENTS_PLUGIN_URL .  '/img/demo_qrcode.png' ?>" alt="" class="qr-code" style="display:inline-block;position:absolute; top: <?php echo $qrcode_top; ?>px;left:<?php echo $qrcode_left; ?>px" width="100" height="100">

        <img src="<?php echo $ticketimage ?>" alt="" class="ticket_bg" width="700" height="352">
    </div>

    <div class="additional-info">
        <p><b>Zusammenfassung:</b>
            <span><?php echo $this->ticketmaster_general_options['additional_info']; ?></span>
        </p>

        <b>Zahlungsmethode:</b> Demo Kreditkarte <br>
        <b>Versandart:</b> Download <br>

        <b>Ihr Hinweis:</b><?php echo $this->ticketmaster_general_options['additional_info']; ?>
    </div>

    <div class="footer">

        <div class="footer-box">
            <small><?php echo nl2br($this->ticketmaster_general_options['footer'][1]); ?></small>
        </div>
        <div class="footer-box">
            <small><?php echo nl2br($this->ticketmaster_general_options['footer'][2]); ?></small>
        </div>
        <div class="footer-box">
            <small><?php echo nl2br($this->ticketmaster_general_options['footer'][3]); ?></small>
        </div>
    </div>
</div>