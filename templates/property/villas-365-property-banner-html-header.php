<?php
wp_enqueue_style('_villas-365-fontawesome', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-fontawesome.css", [], "5.9.0");
wp_enqueue_style('_villas-365-fancybox-styles', VILLAS_365_PLUGIN_URL . "public/assets/libs/fancybox/fancybox.css", [], "3.5.7");
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", ["_villas-365-fontawesome", "_villas-365-fancybox-styles"], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-property-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property.css", ["_villas-365-styles"], VILLAS_365_VERSION);

wp_enqueue_script('_villas-365-bootstrap', VILLAS_365_PLUGIN_URL . "public/assets/libs/bootstrap.js", ["jquery"], "4.3.1", true);
wp_enqueue_script('_villas-365-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-scripts.js", ['jquery', '_villas-365-bootstrap'], VILLAS_365_VERSION, true);
wp_enqueue_script('_villas-365-fancybox', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-fancybox.js", ["jquery", "_villas-365-scripts"], "3.5.7", true);
wp_enqueue_script('_villas-365-property-banner', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-banner.js", ["jquery", "_villas-365-scripts", "_villas-365-fancybox"], VILLAS_365_VERSION, true);