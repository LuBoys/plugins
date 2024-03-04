<?php

function lo_seo_add_meta_boxes() {
    add_meta_box('lo_seo_meta', 'SEO Lucas Ozdemir', 'lo_seo_meta_callback', array('post', 'page'), 'normal', 'high');
}


function lo_seo_meta_callback($post) {
    // Sécurité
    wp_nonce_field('lo_seo_save_meta_data', 'lo_seo_meta_nonce');

    // Récupération des valeurs
    $seo_title = get_post_meta($post->ID, '_lo_seo_title', true);
    $seo_description = get_post_meta($post->ID, '_lo_seo_description', true);
    $seo_keywords = get_post_meta($post->ID, '_lo_seo_keywords', true);

    // HTML
    echo '<label for="lo_seo_title">Titre SEO</label>';
    echo '<input type="text" id="lo_seo_title" name="lo_seo_title" value="' . esc_attr($seo_title) . '" class="widefat">';
    
    echo '<label for="lo_seo_description">Description SEO</label>';
    echo '<textarea id="lo_seo_description" name="lo_seo_description" class="widefat" rows="4">' . esc_textarea($seo_description) . '</textarea>';
    
    echo '<label for="lo_seo_keywords">Mots-clés SEO</label>';
    echo '<input type="text" id="lo_seo_keywords" name="lo_seo_keywords" value="' . esc_attr($seo_keywords) . '" class="widefat">';
}


add_action('add_meta_boxes', 'lo_seo_add_meta_boxes');
