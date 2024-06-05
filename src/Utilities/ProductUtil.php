<?php

namespace Ras\Utilities;

use DOMDocument;
use DOMXPath;
use WC_Product;
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

    public function get_product_html(): void
    {
        global $post;

        $post = get_post($this->product_id);

        $this->render_content_for_template($post);
    }

    public function get_product_list_container_html(): string
    {
        ob_start();

        wc_get_template( 'loop/loop-start.php' );

        return trim(ob_get_clean());
    }

    public function get_product_tag(): ?string
    {
        ob_start();

        $this->get_product_html();

        $productHtml = ob_get_clean();

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($productHtml);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $elements = $xpath->query("//*[contains(@class, 'post-')]");

        $productTag = null;
        foreach ($elements as $element) {
            $classes = explode(' ', $element->getAttribute('class'));
            foreach ($classes as $class) {
                if (preg_match('/^post-\d+$/', $class)) {
                    $productTag = $element->nodeName;
                    break;
                }
            }
        }

        return $productTag;
    }

    public function get_product_link_html(): string
    {
        ob_start();

        woocommerce_template_loop_product_link_open();

        return trim(ob_get_clean());
    }

    public function get_product_promo_tag(): string
    {
        ob_start();

        woocommerce_show_product_loop_sale_flash();

        return trim(ob_get_clean());
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
    }
}