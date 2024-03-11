<?php

namespace Ras\Hooks;

use Ras\Utilities\RasSettings;

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

class CheckVersionHook
{
    public function __construct()
    {
        $this->ras_check_wordpress_version();
        $this->ras_check_woocommerce_version();
    }

    /**
     * @return void
     */
    private function ras_check_woocommerce_version(): void
    {
        if (version_compare(WC()->version, RasSettings::MIN_SUPPORTED_WOOCOMMERCE_VERSION, '<')) {
            deactivate_plugins($GLOBALS['RAS']['PATH'] . $GLOBALS['RAS']['PLUGIN_FILENAME']);
        }
    }

    /**
     * @return void
     */
    private function ras_check_wordpress_version(): void
    {
        if (version_compare(get_bloginfo('version'), RasSettings::MIN_SUPPORTED_WORDPRESS_VERSION, '<')) {
            deactivate_plugins($GLOBALS['RAS']['PATH'] . $GLOBALS['RAS']['PLUGIN_FILENAME']);
        }
    }
}