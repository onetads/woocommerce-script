<?php

namespace Ras\Hooks;

use Ras\Utilities\ProductUtil;
use WP_REST_Request;
use WP_REST_Response;

class ProductHook
{
    public function __construct()
    {
    }

    public function ras_get_product_html(WP_REST_Request $request)
    {
        $product_id = $request->get_param('product_id');
        $view = $request->get_param('view');

        $productUtil = new ProductUtil($product_id);
        if (!$productUtil->is_product_in_stock()) {
            return new WP_REST_Response(
                'Product not found',
                '404'
            );
        }

//        $productUtil->get_product_html();

//        $productUtil->get_related_html();

        $productUtil->get_product_cat_html();
    }


}