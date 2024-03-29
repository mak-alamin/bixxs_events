<?php

function bixxs_events_enqueue_scripts()
{
    wp_enqueue_style('admin-main-css', BIXXS_EVENTS_PLUGIN_URL . '/admin/css/admin-main.css', array(), time(), 'all');

    wp_enqueue_script('admin-main-js', BIXXS_EVENTS_PLUGIN_URL . '/admin/js/admin-main.js', array('jquery', 'media-upload'), time(), true);

    wp_localize_script('admin-main-js', 'BixxsEventsData', array(
        'ajaxUrl' => admin_url('admin-ajax.php')
    ));

    wp_enqueue_style('bixxs-datetime-picker', plugins_url('/css/jquery.datetimepicker.css', __FILE__), null, null, 'all');


    wp_enqueue_script('bixxs-frontend-main', plugins_url('/js/main.js', __FILE__), array('jquery'), time(), true);

    wp_enqueue_script('bixxs-momentjs', '//momentjs.com/downloads/moment-with-locales.min.js', array('jquery'), time(), true);

    wp_enqueue_script('bixxs-datetime_picker', plugins_url('/js/jquery.datetimepicker.full.min.js', __FILE__), array('jquery', 'bixxs-momentjs'), time(), true);
}
add_action('wp_enqueue_scripts', 'bixxs_events_enqueue_scripts');
add_action('admin_enqueue_scripts', 'bixxs_events_enqueue_scripts');
