<?php

namespace Ras\Utilities;


use Ras\Exceptions\RasBlockNotFoundException;
use WC_Product;
use WP_Block_Patterns_Registry;
use WP_Post;

class ProductUtil
{
    private int $product_id;

    private const TEMPLATE_PART_SLUG = 'content';
    private const TEMPLATE_PART_NAME = 'product';

    public function __construct(int $product_id)
    {
        $this->product_id = $product_id;
    }


    /**
     * @return bool
     */
    public function is_product_in_stock(): bool
    {
        /** @var WC_Product $product */
        $product = wc_get_product($this->product_id);

        if (!$product) {
            return false;
        }

        if (!$product->is_in_stock()) {
            return false;
        }

        return true;
    }

    public function get_product_html() {
        global $post;

        $post = get_post($this->product_id);

        $this->render_content_for_template($post);
    }

    /**
     * @param WP_Post $post
     * @return void
     */
    private function render_content_for_template(
        WP_Post $post
    ): void
    {
        setup_postdata($post);

        wc_get_template_part(self::TEMPLATE_PART_SLUG, self::TEMPLATE_PART_NAME);

        wp_reset_postdata();

        exit();
    }
}