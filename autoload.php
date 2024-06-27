<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'src/Utilities/RasSettings.php';
require_once plugin_dir_path(__FILE__) . 'src/Hooks/CheckVersionHook.php';
require_once plugin_dir_path(__FILE__) . 'src/Hooks/ProductHook.php';
require_once plugin_dir_path(__FILE__) . 'src/Utilities/ProductUtil.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';