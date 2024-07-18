<?php

namespace RasOnet\Hooks;

use RasOnet\Utilities\RasOnetSettings;

class RasOnetCheckVersionHook
{
    private $plugin_name;

    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
        $this->rasonet_check_wordpress_version();
        $this->rasonet_check_woocommerce_version();
    }

    /**
     * @return void
     */
    private function rasonet_check_woocommerce_version(): void
    {
        if (!class_exists(RasOnetSettings::WOOCOMMERCE_CLASS_NAME)) {
            deactivate_plugins($this->plugin_name, true);
            return;
        }

        if (version_compare(WC()->version, RasOnetSettings::MIN_SUPPORTED_WOOCOMMERCE_VERSION, '<')) {
            deactivate_plugins($this->plugin_name, true);
        }
    }

    /**
     * @return void
     */
    private function rasonet_check_wordpress_version(): void
    {
        if (version_compare(get_bloginfo('version'), RasOnetSettings::MIN_SUPPORTED_WORDPRESS_VERSION, '<')) {
            deactivate_plugins($this->plugin_name, true);
        }
    }
}