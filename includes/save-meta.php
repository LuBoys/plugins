<?php

function lo_seo_save_postdata($post_id) {
    if (!isset($_POST['lo_seo_meta_nonce']) || !wp_verify_nonce($_POST['lo_seo_meta_nonce'], 'lo_seo_save_meta_data')) {
        return;
    }

    if (array_key_exists('lo_seo_title', $_POST)) {
        update_post_meta($post_id, '_lo_seo_title', sanitize_text_field($_POST['lo_seo_title']));
    }

    if (array_key_exists('lo_seo_description', $_POST)) {
        update_post_meta($post_id, '_lo_seo_description', sanitize_text_field($_POST['lo_seo_description']));
    }

    if (array_key_exists('lo_seo_keywords', $_POST)) {
        update_post_meta($post_id, '_lo_seo_keywords', sanitize_text_field($_POST['lo_seo_keywords']));
    }
}

add_action('save_post', 'lo_seo_save_postdata');
