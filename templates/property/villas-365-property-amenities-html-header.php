<?php
wp_enqueue_style('_villas-365-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-styles.css", [], VILLAS_365_VERSION);
wp_enqueue_style('_villas-365-property-amenity-styles', VILLAS_365_PLUGIN_URL . "public/assets/css/villas-365-property-amenities.css", ["_villas-365-styles"], VILLAS_365_VERSION);

wp_enqueue_script('_villas-365-property-amenity-scripts', VILLAS_365_PLUGIN_URL . "public/assets/js/villas-365-property-amenities.js", ["jquery"], VILLAS_365_VERSION, true);