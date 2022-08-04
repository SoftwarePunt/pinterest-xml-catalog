<?php

namespace SoftwarePunt\PinterestXmlCatalog\Enums;

use SoftwarePunt\PinterestXmlCatalog\Models\ProductData;

/**
 * The condition of your product at time of sale
 *
 * @see ProductData::$condition
 */
class ProductCondition
{
    /**
     * Brand new, original, unopened packaging
     */
    const New = 'New';
    /**
     * Professionally restored to working order, comes with a warranty, may or may not have the original packaging
     */
    const Refurbished = 'Refurbished';
    /**
     * Previously used, original packaging opened or missing
     */
    const Used = 'Used';
}