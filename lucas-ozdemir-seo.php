<?php
/*
Plugin Name: Lucas Ozdemir SEO
Description: Un plugin WordPress pour l'optimisation SEO des pages et articles.
Version: 1.0
Author: Lucas Ozdemir
*/

if (!defined('ABSPATH')) {
    exit;
}

// Inclure les fichiers requis avec vérification
$admin_page_path = plugin_dir_path(__FILE__) . 'admin/admin-page.php';
$meta_boxes_path = plugin_dir_path(__FILE__) . 'includes/meta-boxes.php';
$save_meta_path = plugin_dir_path(__FILE__) . 'includes/save-meta.php';

if (file_exists($admin_page_path)) {
    require_once $admin_page_path;
}

if (file_exists($meta_boxes_path)) {
    require_once $meta_boxes_path;
}

if (file_exists($save_meta_path)) {
    require_once $save_meta_path;
}

function lo_seo_admin_scripts() {
    // Enqueue your stylesheets and scripts
    
    // Ajouter la classe spécifique au corps de la page d'administration
    wp_enqueue_style('lo-seo-plugin-specific', false, array(), null);
    wp_add_inline_style('lo-seo-plugin-specific', '.wrap { max-width: 800px; margin: 20px auto; padding: 20px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px; }');
    wp_add_inline_style('lo-seo-plugin-specific', 'h1 { font-size: 24px; margin-bottom: 20px; }');
    wp_add_inline_style('lo-seo-plugin-specific', 'h3 { font-size: 18px; margin-bottom: 10px; }');
    wp_add_inline_style('lo-seo-plugin-specific', 'input[type="text"], textarea { width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }');
    wp_add_inline_style('lo-seo-plugin-specific', 'input[type="submit"] { background-color: #0073aa; color: #fff; border: none; border-radius: 3px; padding: 10px 20px; cursor: pointer; }');
    wp_add_inline_style('lo-seo-plugin-specific', 'input[type="submit"]:hover { background-color: #005e8a; }');
    wp_add_inline_style('lo-seo-plugin-specific', '.updated { background-color: #f3f3f3; border-left: 4px solid #46b450; padding: 10px; margin-top: 20px; border-radius: 3px; }');

    // Ajouter une classe spécifique au corps de la page d'administration
    add_filter('admin_body_class', 'lo_seo_admin_body_class');
}

function lo_seo_admin_body_class($classes) {
    // Ajouter une classe spécifique au corps de la page d'administration
    $classes .= ' lo-seo-admin-page';
    return $classes;
}

add_action('admin_enqueue_scripts', 'lo_seo_admin_scripts');



// Internationalisation du plugin
function lo_seo_load_textdomain() {
    load_plugin_textdomain('lucas-ozdemir-seo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'lo_seo_load_textdomain');
