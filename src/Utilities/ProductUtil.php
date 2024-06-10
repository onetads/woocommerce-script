<?php

namespace RasOnet\Utilities;

use DOMDocument;
use DOMXPath;
use WC_Product;
use WP_Post;

class ProductUtil
{
    private int $product_id;

    private const TEMPLATE_PART_SLUG = 'content';
    private const TEMPLATE_PART_NAME = 'product';

    private const PROMO_TAG_LABEL = '%TAG_LABEL%';

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

    public function get_product_html(): string
    {
        global $post;

        $post = get_post($this->product_id);

        return $this->get_content_for_template($post);
    }

    public function get_product_list_container_tag(): string
    {
        ob_start();

        wc_get_template( 'loop/loop-start.php' );

        $html = trim(ob_get_clean());

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        // Recursive function to find the deepest child
        function findDeepest($element) {
            if ($element->hasChildNodes()) {
                return findDeepest($element->lastChild);
            } else {
                return $element;
            }
        }

        $deepestChild = findDeepest($dom);

        if (!$deepestChild) {
            return '';
        }

        return $deepestChild->tagName;
    }

    public function get_product_tag(): ?string
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($this->get_product_html());
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
        global $product;

        return esc_url(apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product ));
    }

    public function get_product_promo_tag(): array
    {
        global $product;

        $product->set_sale_price(0);

        $product->set_date_on_sale_from();

        $product->set_date_on_sale_to();

        ob_start();

        woocommerce_show_product_loop_sale_flash();

        $html = trim(ob_get_clean());

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $elements = $xpath->query("//*[text()[normalize-space() != '']]");

        foreach ($elements as $element) {
            $element->nodeValue = self::PROMO_TAG_LABEL;
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $innerHTML = '';
        foreach ($body->childNodes as $child) {
            $innerHTML .= $dom->saveHTML($child);
        }

        return [
            'original' => $html,
            'substitute' => $innerHTML,
        ];
    }

    /**
     * @param WP_Post $post
     * @return string
     */
    private function get_content_for_template(
        WP_Post $post
    ): string
    {
        setup_postdata($post);

        global $product;

        $product->set_sale_price(0);

        $product->set_date_on_sale_from();

        $product->set_date_on_sale_to();

        ob_start();

        wc_get_template_part(self::TEMPLATE_PART_SLUG, self::TEMPLATE_PART_NAME);

        $contentTemplate = ob_get_clean();

        wp_reset_postdata();

        return $contentTemplate;
    }
}