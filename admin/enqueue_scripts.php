<?php

/**
 * This file loads necessary styles and scripts for the Ticket Master Plugin
 */

function bixxs_events_load_admin_scripts()
{

    wp_enqueue_style('admin-main-css', plugins_url('/css/admin-main.css', __FILE__), array(), time(), 'all');

    wp_enqueue_script('media-upload');
    wp_enqueue_media();

    wp_enqueue_script('admin-main-js', plugins_url('/js/admin-main.js', __FILE__), array('jquery', 'media-upload'), time(), true);

    wp_enqueue_script('product-metadata-js', plugins_url('/js/product_metadata.js', __FILE__), array('jquery',), time(), true);
}
add_action('admin_enqueue_scripts', 'bixxs_events_load_admin_scripts');
