<?php

namespace Ras\Utilities;


use WC_Product;

class ProductUtil
{
    private int $product_id;

    public function __construct(int $product_id)
    {
        $this->product_id = $product_id;
    }


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

    function get_product_html()
    {
        global $product;

        global $post;

        global $_wp_current_template_content, $_wp_current_template_id;

        $product = wc_get_product($this->product_id);

        locate_block_template('', 'archive', ['archive-product.php']);

        $post = get_post($product->get_id());

        $blocks = parse_blocks($_wp_current_template_content);

        $postTemplateBlock = $this->findCorePostTemplateBlock($blocks);

        $block_content = '';

        foreach ($postTemplateBlock['innerBlocks'] as $blockTest) {
            $block_content .= render_block($blockTest);
        }

        $enhanced_pagination = false;

        // Wrap the render inner blocks in a `li` element with the appropriate post classes.
        $post_classes = implode( ' ', get_post_class( 'wp-block-post' ) );

        $inner_block_directives = $enhanced_pagination ? ' data-wp-key="post-template-item-' . $post->ID . '"' : '';

        echo '<li' . $inner_block_directives . ' class="' . esc_attr( $post_classes ) . '">' . $block_content . '</li>';

        die();

    }

    function get_related_html()
    {

        global $product;

        global $post;

        global $_wp_current_template_content, $_wp_current_template_id;

        $product = wc_get_product($this->product_id);

        $post = get_post($product->get_id());

        locate_block_template('', 'archive', ['single-product.php']);

        $blocks = parse_blocks($_wp_current_template_content);

        $postTemplateBlock = $this->findCorePostTemplateBlock($blocks);

        $block_content = '';

        foreach ($postTemplateBlock['innerBlocks'] as $blockTest) {
            $block_content .= render_block($blockTest);
        }

        $enhanced_pagination = false;

        // Wrap the render inner blocks in a `li` element with the appropriate post classes.
        $post_classes = implode( ' ', get_post_class( 'wp-block-post' ) );

        $inner_block_directives = $enhanced_pagination ? ' data-wp-key="post-template-item-' . $post->ID . '"' : '';

        echo '<li' . $inner_block_directives . ' class="' . esc_attr( $post_classes ) . '">' . $block_content . '</li>';

        die();
    }


    function get_product_cat_html()
    {
        global $product;

        global $post;

        global $_wp_current_template_content, $_wp_current_template_id;

        $product = wc_get_product($this->product_id);

        locate_block_template('', 'archive', ['taxonomy-product_cat.php']);

        $post = get_post($product->get_id());

        $blocks = parse_blocks($_wp_current_template_content);

        $postTemplateBlock = $this->findCorePostTemplateBlock($blocks);

        $block_content = '';

        foreach ($postTemplateBlock['innerBlocks'] as $blockTest) {
            $block_content .= render_block($blockTest);
        }

        $enhanced_pagination = false;

        // Wrap the render inner blocks in a `li` element with the appropriate post classes.
        $post_classes = implode( ' ', get_post_class( 'wp-block-post' ) );

        $inner_block_directives = $enhanced_pagination ? ' data-wp-key="post-template-item-' . $post->ID . '"' : '';

        echo '<li' . $inner_block_directives . ' class="' . esc_attr( $post_classes ) . '">' . $block_content . '</li>';

        die();

    }

    private function findCorePostTemplateBlock($blocks)
    {
        $blockName = 'core/post-template';
        $postTemplateBlock = null;

        foreach ($blocks as $block) {
            if ($block['blockName'] == $blockName) {
                $postTemplateBlock = $block;
            }

            if ($postTemplateBlock) {
                break;
            }

            if (!empty($block['innerBlocks'])) {
                $postTemplateBlock = $this->findCorePostTemplateBlock($block['innerBlocks']);
            }
        }

        return $postTemplateBlock;
    }
}