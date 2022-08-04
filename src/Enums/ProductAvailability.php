<?php

namespace SoftwarePunt\PinterestXmlCatalog\Enums;

use SoftwarePunt\PinterestXmlCatalog\Models\ProductData;

/**
 * Your product's availability.
 *
 * @see ProductData::$availability
 */
class ProductAvailability
{
    const InStock = 'In Stock';
    const OutOfStock = 'Out of Stock';
    const Preorder  = 'Preorder';
    const Backorder = 'Backorder';
}