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
}