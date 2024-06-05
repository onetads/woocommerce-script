<?php

namespace Ras\Hooks;

use Ras\Utilities\ProductUtil;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

class ProductHook
{

    /**
     * @param WP_REST_Request $request
     * @return void|WP_REST_Response
     */
    public function ras_get_product_html(WP_REST_Request $request)
    {
        $product_id = $request->get_param('product_id');

        $productUtil = new ProductUtil($product_id);
        if (!$productUtil->is_product_in_stock()) {
            return new WP_REST_Response(
                'Product not found',
                '404'
            );
        }

        $productUtil->get_product_html();

        exit();
    }

    public function return_required_html_elements(): void
    {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => 1,
            'orderby' => 'ID',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_stock_status',
                    'value' => 'instock',
                ],
            ],
        ];

        $query = new WP_Query($args);

        $productId = null;

        if ($query->have_posts()) {
            $query->the_post();

            global $product;

            $productId = $product->get_id();
        }

        wp_reset_postdata();

        if (is_null($productId)) {
            wp_send_json([
                'message' => 'No products found',
            ], 404);
        }

        $productUtil = new ProductUtil($productId);

        wp_send_json([
            'list_container_html' => $productUtil->get_product_list_container_html(),
            'product_tag' => $productUtil->get_product_tag(),
            'link_html' => $productUtil->get_product_link_html(),
            'promo_tag' => $productUtil->get_product_promo_tag(),
        ]);
    }


}