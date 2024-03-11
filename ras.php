<?php
namespace Ras;

use Ras\Hooks\ProductHook;
use WP_REST_Server;

require_once $GLOBALS['RAS']['PATH'] . 'src/Hooks/ProductHook.php';
require_once $GLOBALS['RAS']['PATH'] . 'src/Utilities/ProductUtil.php';

class Ras
{

    public function __construct()
    {
        $GLOBALS['RAS']['PATH'] = plugin_dir_path(__FILE__);
        $GLOBALS['RAS']['PLUGIN_FILENAME'] = basename(__FILE__);
    }

    public function ras_register_routes()
    {
        register_rest_route('ras', '/get-product-html', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'ras_get_product_html'],
            'args' => [
                'product_id' => [
                    'required' => true,
                    'validate_callback' => function ($param) {
                        return is_numeric($param);
                    }
                ],
                'view' => [
                    'required' => true,
                ],
            ],
            'permission_callback' => '__return_true'
        ]);
    }

    public function ras_get_product_html($request)
    {
        $productHook = new ProductHook();
        return $productHook->ras_get_product_html($request);
    }
}
add_action('rest_api_init', [new Ras(), 'ras_register_routes']);