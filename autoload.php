<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'src/Utilities/RasOnetSettings.php';
require_once plugin_dir_path(__FILE__) . 'src/Hooks/RasOnetCheckVersionHook.php';
require_once plugin_dir_path(__FILE__) . 'src/Hooks/RasOnetProductHook.php';
require_once plugin_dir_path(__FILE__) . 'src/Utilities/RasOnetProductUtil.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';