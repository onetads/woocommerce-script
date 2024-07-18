<?php
/**
 * @package RasOnet
 * @version 1.0
 * @license GPLv3
 *
 * Plugin Name: Retail Media Network - Onet Ads
 * Plugin URI: https://panel.onetads.pl
 * Description: Błyskawiczna integracja Woocommerce z Retail Media Network w Onet Ads. Wykorzystaj ten plugin, żeby szybko i intuicyjnie zaimplementować nasze formaty reklamowe w swoim sklepie.
 * Author: Ringier Axel Springer Polska sp. z o.o.
 * Text Domain: onet-ads-rmn
 * Author URI: https://ringieraxelspringer.pl/
 * Version: 1.0
 * Requires Plugins: woocommerce
 */

namespace RasOnet;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use RasOnet\Hooks\RasOnetCheckVersionHook;
use RasOnet\Hooks\RasOnetProductHook;
use WP_REST_Server;

include 'autoload.php';

class RasOnet
{

    public function rasonet_register_routes()
    {
        register_rest_route('ras', '/get-product-html', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'rasonet_get_product_html'],
            'args' => [
                'product_id' => [
                    'required' => true,
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
            ],
            'permission_callback' => '__return_true'
        ]);

        register_rest_route('ras', '/get-html-templates', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'rasonet_get_product_list_html'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function rasonet_get_product_html($request): void
    {
        $productHook = new RasOnetProductHook();

        $productHook->rasonet_get_product_html($request);
    }

    public function rasonet_get_product_list_html(): void
    {
        $productHook = new RasOnetProductHook();

        $productHook->rasonet_return_required_html_elements();
    }

    /**
     * @return void
     */
    public function rasonet_check_versions(): void
    {
        new RasOnetCheckVersionHook(plugin_basename( __FILE__ ));
    }
}

add_action('rest_api_init', [new RasOnet(), 'rasonet_register_routes']);
add_action('plugins_loaded', [new RasOnet(), 'rasonet_check_versions']);