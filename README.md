# pinterest-xml-catalog

**Unofficial PHP Library for generating Pinterest Catalogs in XML (RSS 2.0, ATOM 1.0) format**

This can be used to generate a [data source](https://help.pinterest.com/en/business/article/data-source-ingestion) for
daily product ingestion by Pinterest. You must already have
a [business account](https://help.pinterest.com/en/business/article/get-a-business-account) and
a [claimed website](https://help.pinterest.com/en/business/article/claim-your-website) that meets
their [merchant guidelines](https://policy.pinterest.com/en/merchant-guidelines).

You can use this to help convert product data to XML, but you'll have to serve the output yourself.

## Installation

Add the library to your project via [Composer](https://getcomposer.org/download/):

```bash
composer require softwarepunt/pinterest-xml-catalog
```

### Requirements

- PHP 7.4+
- `ext-dom`

## Usage

### Creating an XML catalog

Collect your product data, and then feed it into a `XmlCatalog` instance.

```php
<?php

use SoftwarePunt\PinterestXmlCatalog\XmlCatalog;
use SoftwarePunt\PinterestXmlCatalog\Models\ProductData;

// Create a new, blank catalog
$xmlCatalog = new XmlCatalog();

// Gather product data
$product = new ProductData();
$product->id = "4000086";
$product->title = "Illuminating Makeup Mirror";
$product->description = "A ring light mirror with 23 LED strip lights.";
$product->link = "https://www.example.com/cat/illuminating-makeup-mirror";
$product->imageLink = "https://www.example.com/media/catalog/product/image.jpg";
$product->additionalImageLink = "https://www.example.com/media/catalog/product/image_side.jpg";
$product->price = "14,99 EUR";

// Add products to catalog (can be ProductData, other object, or data array)
$xmlCatalog->addProduct($product);

// Convert to XML and serve as response
echo $xmlCatalog->toXmlString();

```

### Product data

Pinterest uses the Google Merchant Data [specifications](https://support.google.com/merchants/answer/7052112?hl=en) for
RSS 2.0, which you can refer to for documentation on the specific fields used.

👉 This library currently only targets fields that are in the
Pinterest [sample](https://help.pinterest.com/sub/helpcenter/assets/pinterest_product_sample_xml_feed.xml.zip)
or [docs](https://help.pinterest.com/en/business/article/before-you-get-started-with-catalogs), not the full
Google-supported set of fields.

Using the `ProductData` class is recommended because it contains relevant phpdocs to make your life easier. You can also
pass your own associative array or object for each product to `addProduct()` if you prefer.

If you are adding products from your own object or array, you should make sure that the property names / array keys can
be understood by the library. For example, if you want to set the Google Product Category, valid keys to set it
are `googleProductCategory`, `g:google_product_category` and `google_product_category`.

### Required fields

When filling products, the following fields are always required:

| Field name     | XML Element      | Description                                                                                                           |
|----------------|------------------|-----------------------------------------------------------------------------------------------------------------------|
| `id`           | `g:id`           | Product ID. Always required. Example: "DS0294-L".                                                                     |
| `title`        | `title`          | Product name. Always required. Example: "Women’s denim shirt, large".                                                 |
| `description`  | `description`    | Product description. Always required. Example: "Casual fit denim shirt made with the finest quality Japanese denim.". |
| `link`         | `link`           | Product URL. Always required. Example: "https://www.example.com/cat/womens-clothing/denim-shirt-0294".                |
| `imageLink`    | `g:image_link`   | Primary image URL. Always required. Example: "https://scene.example.com/image/image.jpg".                             |
| `availability` | `g:availability` | Product availability. Always required. Must be "in stock" (default), "out of stock" or "preorder".                    |
| `price`        | `g:price`        | ISO 4217 price specification. Always required.                                                                        |

### Optional fields

These fields are optional and can be used to enrich the product with additional information.

| Field name              | XML Element                 | Description                                                                                                                                                                         |
|-------------------------|-----------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `additionalImageLink`   | `g:additional_image_link`   | Comma-separated list of additional images for your product. A new pin will be created for each additional image link.                                                               |
| `mobileLink`            | `g:mobile_link`             | The mobile-optimized version of your landing page.                                                                                                                                  |
| `salePrice`             | `sale_price`                | The discounted price of the product. Must be lower than the regular price.                                                                                                          |
| `productType`           | `g:product_type`            | The categorization of your product based on your custom product taxonomy. Up to five subcategories must be sent separated by ` > `. The `>` must be wrapped by spaces.              |
| `brand`                 | `brand`                     | The brand of the product.                                                                                                                                                           |
| `condition`             | `g:condition`               | The condition of the product.                                                                                                                                                       |
| `googleProductCategory` | `g:google_product_category` | The categorization of the product based on the standardized [Google Product Taxonomy](https://help.pinterest.com/sub/helpcenter/assets/Google_product_category_taxonomy_EN_US.xls). | 