<?php
require_once __DIR__ . '/pdf_fields_options.php';
?>

<style>
    /* PDF Template Style */
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
        width: 700px;
        height: 350px;
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
            <img src="<?php echo $this->ticketmaster_options['general_settings']['logo']; ?>" alt="" width="120">
        </div>
        <div class="header-right">
            <b>Bestellung:</b> 27.12.2020<br>
            <b>Code-ID:</b> 1489<br>
            <b>Name:</b> Michael Lenuweit<br>
        </div>
        <div class="clear-fix"></div>
    </div>

    <div class="header-address">
        <p><b><?php echo $this->ticketmaster_options['general_settings']['heading']; ?></b>
        <p>

            <span>Michael Lenuweit</span><br>
            <span>Marie-Curie-Strasse 6</span><br>
            <span>47475 Kamp-Lintfort</span><br><br>
            <span><?php echo $this->ticketmaster_options['general_settings']['info']; ?> </span>
    </div>

    <div class="ticket-body">
        <?php if ($show_pdf_productname) { ?>
            <b style="position:absolute; top:50px; left: 50px;">Name: Ticket Name</b>
        <?php } ?>

        <?php if ($show_pdf_order_date) { ?>
            <span style="position:absolute; top: 80px; left: 50px;">Bestelldatum: 28.06.2021</span>
        <?php } ?>

        <?php if ($show_pdf_price) { ?>
            <span style="position:absolute; top:110px; left: 50px;">Preis: €20 </span>
        <?php } ?>

        <?php if ($show_pdf_menge) { ?>
            <span style="position:absolute; top:230px; left:50px;">Menge: 1</span>
        <?php } ?>

        <?php if ($show_pdf_reserve_time) { ?>
            <span style="position:absolute;top: 140px;left: 50px;" class="reservation-date">Termin der Re servierung: 31.12.2021</span>
        <?php } ?>

        <?php if ($show_pdf_veranstalter) { ?>
            <span style="position:absolute;top: 170px;left: 50px;" class="veranstalter">Veranstalter: Veranstalter</span>
        <?php } ?>

        <?php if ($show_pdf_ort_veranstalter) { ?>
            <span style="position:absolute;top: 200px;left: 50px;" class="ort">Ort der Veranstaltung: Ort der Veranstaltung</span>
        <?php } ?>

        <?php if ($show_pdf_ticketnumber) { ?>
            <span style="position:absolute;top: 2600px;left: 50px;" class="ticket-number">Ticketnummer: ist gleiche wie Bestellnummer</span>
        <?php } ?>

        <img src="<?php echo BIXXS_EVENTS_PLUGIN_URL .  '/img/demo_qrcode.png' ?>" alt="" class="qr-code" style="display:inline-block;position:absolute; top:240px;left:580px;" width="100" height="100">

        <img src="https://dev2.bixxs.de/wp-content/uploads/2022/05/tp-tickets.png" alt="" class="ticket_bg" width="700" height="352">
    </div>

    <div class="additional-info">
        <p><b>Zusammenfassung:</b>
            <span><?php echo $this->ticketmaster_options['general_settings']['additional_info']; ?></span>
        </p>

        <b>Zahlungsmethode:</b> Demo Kreditkarte <br>
        <b>Versandart:</b> Download <br>

        <b>Ihr Hinweis:</b><?php echo $this->ticketmaster_options['general_settings']['additional_info']; ?>
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