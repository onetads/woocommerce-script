<?php

use Ras\Hooks\CheckVersionHook;

require_once $GLOBALS['RAS']['PATH'] . 'src/Utilities/RasSettings.php';
require_once $GLOBALS['RAS']['PATH'] . 'src/Hooks/CheckVersionHook.php';

class Ras
{

    public function __construct()
    {
        $GLOBALS['RAS']['PATH'] = plugin_dir_path(__FILE__);
        $GLOBALS['RAS']['PLUGIN_FILENAME'] = basename(__FILE__);
    }

    /**
     * @return void
     */
    public function ras_check_versions(): void
    {
        $plugin_data = get_plugin_data(plugin_dir_path(__FILE__) . $GLOBALS['RAS']['PLUGIN_FILENAME']);
        $GLOBALS['RAS']['VERSION'] = $plugin_data['Version'];
        new CheckVersionHook();
    }
}

add_action('plugins_loaded', [new Ras(), 'ras_check_versions']);