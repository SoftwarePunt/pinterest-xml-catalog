<?php

namespace SoftwarePunt\PinterestXmlCatalog\Models;

use SoftwarePunt\PinterestXmlCatalog\Enums\ProductAvailability;
use SoftwarePunt\PinterestXmlCatalog\Enums\ProductCondition;

class ProductData extends BaseModel
{
    // -----------------------------------------------------------------------------------------------------------------
    // Basic product data

    /**
     * The user-created unique ID that represents the product. Only Unicode characters are accepted.
     *
     * - Use a unique value for each product. Use the product's SKU where possible.
     * - Keep the ID the same when updating your data.
     * - Use only valid unicode characters. Avoid invalid characters like control, function, or private area characters.
     * - Use the same ID for the same product - across countries or languages.
     *
     * @required
     * @maxlen 127
     * @element g:id
     * @example DS0294-L
     */
    public string $id;

    /**
     * Your product’s name. Max 150 characters.
     *
     * - Accurately describe your product and match the title from your landing page.
     * - Don’t include promotional text like "free shipping," all capital letters, or gimmicky foreign characters.
     * - For variants: include a distinguishing feature such as color or size.
     *
     * @required
     * @maxlen 150
     * @element title
     */
    public string $title;

    /**
     * Your product’s description. Max 5000 characters.
     *
     * - Accurately describe your product and match the description from your landing page.
     * - Don’t include promotional text like "free shipping," all capital letters, or gimmicky foreign characters.
     * - Include only information about the product. Don’t include links to your store, sales information, details about competitors, other products, or accessories.
     * - Use formatting (for example, line breaks, lists, or italics) to format your description.
     *
     * @required
     * @maxlen 5000
     * @element description
     */
    public string $description;

    /**
     * Your product’s landing page.
     *
     * - Use your verified domain name
     * - Start with http or https
     * - Use an encoded URL that complies with RFC 2396 or RFC 1738. For example, a comma would be represented as "%2C"
     * - Don't link to an interstitial page unless legally required
     *
     * @required
     * @element link
     */
    public string $link;

    /**
     * The URL of your product’s main image.
     *
     * - For the image URL:
     *     - Link to the main image of your product
     *     - Start with http or https
     *     - Use an encoded URL that complies with RFC 2396 or RFC 1738. For example, a comma would be represented as "%2C"
     *     - Make sure the URL can be crawled by Google (robots.txt configuration allowing Googlebot and Googlebot-image)
     * - For the image:
     *     - Accurately display the product you're selling
     *     - Use an accepted format: JPEG (.jpg/.jpeg), WebP (.webp), PNG (.png), non-animated GIF (.gif), BMP (.bmp), and TIFF (.tif/.tiff)
     *     - For non-apparel products, use an image of at least 100 x 100 pixels
     *     - For apparel products, use an image of at least 250 x 250 pixels
     *     - Don't submit an image larger than 64 megapixels or a file larger than 16MB
     *     - Don't scale up an image or submit a thumbnail
     *     - Don't include promotional text, watermarks, or borders
     *     - Don't submit a placeholder or a generic image Exceptions:
     *         - In Hardware (632) or Vehicles & Parts (888) categories, illustrations are accepted.
     *         - In any paint category, single color images are allowed
     *
     * @required
     * @element g:image_link
     */
    public string $imageLink;

    /**
     * The URL(s) of additional images for your product. Must be comma separated.
     *
     * @var string|array|null
     * @optional
     * @maxlen 2000
     * @element g:additional_image_link
     */
    public $additionalImageLink = null;

    /**
     * Your product’s mobile-optimized landing page when you have a different URL for mobile and desktop traffic.
     *
     * - Must meet the requirements for the link [link] attribute.
     *
     * @optional
     * @maxlen 2000
     * @element g:mobile_link
     */
    public ?string $mobileLink = null;

    // -----------------------------------------------------------------------------------------------------------------
    // Price & availability

    /**
     * Your product's availability.
     *
     * - Accurately submit the product's availability and match the availability from your landing page and checkout pages.
     * - Provide the availability date [availability_date] attribute (with a value up to 1 year in the future) if the availability is set to preorder or backorder
     *
     * @see ProductCondition
     * @required
     * @element g:availability
     */
    public string $availability = ProductAvailability::InStock;

    /**
     * Your product's price (numeric with ISO 4217 currency).
     *
     * - Accurately submit the product's price and currency, and match with the price from your landing page and at checkout
     * - Make sure that your landing page and the checkout pages include the price in the currency of the country of sale prominently and in a place that's straightforward to find
     * - Ensure that the product can be purchased online for the submitted price
     * - Make sure that any customer in the country of sale can buy the product for the submitted price, and without paying for a membership
     *     - Add any minimum order value in your shipping settings
     * - Don't submit a price of 0 (a price of 0 is allowed for mobile devices sold with a contract)
     * - For products sold in bulk quantities, bundles, or multipacks
     * - Submit the total price of the minimum purchasable quantity, bundle, or multipack
     * - For the US and Canada
     *     - Don't include tax in the price
     * - For all other countries
     *     - Include value added tax (VAT) or Goods and Services Tax (GST) in the price
     *
     * @example 15.00 USD
     * @required
     * @element g:price
     */
    public string $price;

    /**
     * Your product's special / sale price (numeric with ISO 4217 currency).
     *
     * - Meet the requirements for the price [price] attribute
     * - Submit this attribute (sale price )in addition to the price [price] attribute set to the non-sale price
     * - Accurately submit the product's sale price, and match the sale price with your landing page and the checkout pages
     *
     * @example 14.99 USD
     * @var string|null
     * @element sale_price
     */
    public ?string $salePrice = null;

    // -----------------------------------------------------------------------------------------------------------------
    // Product category

    /**
     * Google-defined product category for your product.
     *
     * - Include only 1 category
     * - Include the most relevant category
     * - Include either the full path of the category or the numerical category ID, but not both. It is recommended to use the category ID.
     * - Include a specific category for certain products:
     *     - Alcoholic beverages must be submitted with one of these categories:
     *         - Food, Beverages & Tobacco > Beverages > Alcoholic Beverages (ID: 499676), or any of its subcategories
     *         - Arts & Entertainment > Hobbies & Creative Arts > Homebrewing & Winemaking Supplies (ID: 577), or any of its subcategories
     *     - Mobile devices sold with contract must be submitted as Electronics > Communications > Telephony > Mobile Phones (ID: 267) for phones or Electronics > Computers > Tablet Computers (ID: 4745) for tablets
     *     - Gift Cards must be submitted as Arts & Entertainment > Party & Celebration > Gift Giving > Gift Cards & Certificates (ID: 53)
     *
     * @example Apparel & Accessories > Clothing > Outerwear > Coats & Jackets
     * @see https://support.google.com/merchants/answer/1705911 (Google product taxonomy)
     * @element g:google_product_category
     */
    public ?string $googleProductCategory = null;

    /**
     * Product category that you define for your product.
     *
     * - Include the full category. For example, include Home > Women > Dresses > Maxi Dresses instead of just Dresses
     * - Only the first product type value will be used to organize bidding and reporting in Google Ads Shopping campaigns
     *
     * @example Tools > Mirrors
     * @maxlen 750
     * @element g:product_type
     */
    public ?string $productType = null;

    // -----------------------------------------------------------------------------------------------------------------
    // Product identifiers

    /**
     * The brand name of the product generally recognized by consumers.
     *
     * - Providing the correct brand for a product will ensure the best user experience and result in the best performance.
     * - Only provide your own brand name as the brand if you manufacture the product or if your product falls into a generic brand category. For example, you could submit your own brand name as the brand if you sell private-label products or customized jewelry.
     * - For products that truly do not have a brand (for example, a vintage dress without a label, generic electronics accessories, etc.) leave this field empty.
     * - Don't submit values such as "N/A", "Generic", "No brand", or "Does not exist".
     * - For compatible products:
     *      - Submit the GTIN and brand from the manufacturer who actually built the compatible product
     *      - Don't provide the Original Equipment Manufacturer (OEM) brand to indicate that your product is compatible with or a replica of the OEM brand's product
     *
     * @example Google
     * @maxlen 70
     * @element brand
     */
    public string $brand;

    // -----------------------------------------------------------------------------------------------------------------
    // Detailed product description

    /**
     * The condition of your product at time of sale.
     * Required if your product is used or refurbished.
     * Optional for new products.
     *
     * @see ProductCondition
     * @required
     * @element g:condition
     */
    public string $condition = ProductCondition::New;

    // -----------------------------------------------------------------------------------------------------------------
    // Shopping campaigns and other configurations

    // -----------------------------------------------------------------------------------------------------------------
    // Destinations

    // -----------------------------------------------------------------------------------------------------------------
    // Shipping

    // -----------------------------------------------------------------------------------------------------------------
    // Tax

    // -----------------------------------------------------------------------------------------------------------------
    // Convert helper

    public static function createFrom($product): ProductData
    {
        if ($product instanceof ProductData)
            return $product;

        if (is_array($product))
            $productArr = $product;
        else if (is_object($product))
            $productArr = (array)$product;
        else
            throw new \InvalidArgumentException("\$product should be an object or an array");

        return new ProductData(null, $productArr);
    }
}