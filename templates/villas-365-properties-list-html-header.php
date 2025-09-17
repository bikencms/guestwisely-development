<?php
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
wp_enqueue_style('_villas-365-chosen-styles', VILLAS_365_PLUGIN_URL . "public/assets/libs/chosen/chosen.css", ["_villas-365-fontawesome"], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-properties-list-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-properties-list.css", ['_villas-365-styles', '_villas-365-chosen-styles'], VILLAS_365_VERSION);

$villas365CSSOverrides = Helpers365::GenerateCSSOverrides();
wp_add_inline_style('_villas-365-styles', $villas365CSSOverrides);

wp_enqueue_script('_villas-365-chosen-scripts', VILLAS_365_PLUGIN_URL . "public/assets/libs/chosen/chosen.js", ['jquery'], VILLAS_365_VERSION, true);
wp_enqueue_script('_villas-365-properties-list-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-properties-list.js", ['jquery', '_villas-365-chosen-scripts'], VILLAS_365_VERSION, true);