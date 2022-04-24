<div id='bixxs_events_settings' class='panel woocommerce_options_panel'>
    <div class='options_group'>
        <?php

        woocommerce_wp_text_input(
            array(
                'id' => 'bixxs_events_price_per_person',
                'label' => 'Preis pro Person',
                'type' => 'number',
                'custom_attributes' => array(
                    'step' => '.01',
                    'min' => '0',
                )
            )
        );

        woocommerce_wp_text_input(
            array(
                'id' => 'bixxs_events_price_per_event',
                'label' => 'Preis pro Veranstaltung',
                'type' => 'number',
                'custom_attributes' => array(
                    'step' => '.01',
                    'min' => '0',
                )
            )
        );

        $ticket_results = $wpdb->get_results(
            "SELECT id, ticketnname FROM {$wpdb->prefix}bixxs_events"
        );

        $ticket_templates = array('0' => __('Auswahl Vorlage', 'ticketmaster'));
        foreach ($ticket_results as $key => $template) {
            $ticket_templates[$template->id] = $template->ticketnname;
        }

        woocommerce_wp_select(
            array(
                'id'      => 'bixxs_events_event_template',
                'label'   => __('Wählen Sie Vorlage', 'ticketmaster'),
                'options' => $ticket_templates,
            )
        );

        //Time Slots
        $bixxs_events_start_time = get_post_meta($post->ID, 'bixxs_events_start_time', true);
        $bixxs_events_end_time = get_post_meta($post->ID, 'bixxs_events_end_time', true);

        ?>
        <!-- Time Format: h:i a -->
        <p>
            <label for="bixxs_events_start_time" style="margin-left:0;">Start Zeit...</label>
            <input type="text" placeholder="z.B <?php echo date('d.m.Y'); ?>" autocomplete="off" id="bixxs_events_start_time" name="bixxs_events_start_time" value="<?php echo $bixxs_events_start_time; ?>">
        </p>

        <p>
            <label for="bixxs_events_end_time" style="margin-left:0;">End Zeit...</label>

            <input type="text" placeholder="z.B. <?php echo date('d.m.Y', strtotime('+30 days', strtotime(str_replace('/', '-', date('d.m.Y'))))) . PHP_EOL; ?>" autocomplete="off" id="bixxs_events_end_time" name="bixxs_events_end_time" value="<?php echo $bixxs_events_end_time; ?>">
        </p>

        <?php

        woocommerce_wp_text_input(
            array(
                'id' => 'bixxs_events_label',
                'label' => 'Beschriftung',
                'placeholder' => 'Gast',
            )
        );

        woocommerce_wp_text_input(
            array(
                'id' => 'bixxs_events_label_plural',
                'label' => 'Beschriftung (Plural)',
                'placeholder' => 'Gäste',
            )
        );

        woocommerce_wp_text_input(array(
            'id' => 'bixxs_events_max_guests',
            'label' => 'Maximale Tickets pro Bestellung:',
            'placeholder' => '5',
            'type' => 'number',
            'custom_attributes' => array(
                'step' => '1',
                'min' => '0',
            )
        ));

        echo ' <h4>Verfügbare Tickets pro Tag</h4>';

        $days = ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'];
        foreach ($days as $day) {
            $day_key = sanitize_title($day);

        ?>
            <div class="ui-tab">
                <div class="tab-title"><?php echo $day; ?></div>
                <div class='tab-content'>
                    <button class="ticketmaster-add-timeslots button button-primary" data-day="<?php echo $day_key; ?>" type="button">Timeslot hinzufügen</button>
                    <div class="timeslots-wrap-<?php echo $day_key; ?>">
                        <?php
                        $timeslots = get_post_meta($post->ID, 'timeslots_selection', true);

                        $timeslots = unserialize($timeslots);

                        if (!empty($timeslots)) {
                            foreach ($timeslots['timeslots'][$day_key] as $key => $timeslot) {
                                list($hour, $minute) = explode(':', $timeslot);
                                $available_tickets = $timeslots['tickets'][$day_key][$key];
                        ?>
                                <div class="time-slot-wrap">
                                    <input type="number" step="1" min="0" max="23" class='hour_time_input' placeholder="HH" value="<?php echo $hour ?>">
                                    <input type="number" step="5" min="0" max="59" class='minute_time_input' placeholder="mm" value="<?php echo $minute; ?>">
                                    <input type="number" name="timeslots_selection[tickets][<?php echo $day_key; ?>][]" value="<?php echo $available_tickets; ?>" placeholder="Tickets">
                                    <input type="hidden" name="timeslots_selection[timeslots][<?php echo $day_key; ?>][]" value="<?php echo $timeslot; ?>">
                                    <button type="button" class="button button-outline-secondary remove-time-slot"> <i class='dashicons dashicons-no '></i> </button>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>



<!--================================== 
Bixxs Events Addon 
===================================-->
<div id='bixxs_events_add_ons' class='panel woocommerce_options_panel'>

    <h3>Veranstaltungen Addons</h3>

    <?php

    $template_addon = array(
        'selection'     => 'number',
        'price_person'  => '',
        'price_event'   => '',
        'label'         => '',
    );

    echo '<div id="bixxs_events_template_addon" class="bixxs-events-hidden">';

    bixxs_events_render_addon_field($template_addon);

    echo '</div>';


    $product = wc_get_product($post->ID);
    $addons =  json_decode($product->get_meta('bixxs_events_fields'), true);

    if ($addons) {
        $loop = 1;
        foreach ($addons as $addon) {
            echo '<div class="bixxs_events_addon_field_wrapper" data-number="' . $loop . '">';

            bixxs_events_render_addon_field($addon, $loop);

            echo '</div>';

            $loop++;
        }
    }
    ?>

    <button id="bixxs_events_add_addon" type="button" class="button button-secondary">Hinzufügen</button>
</div>