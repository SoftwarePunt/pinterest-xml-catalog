# pinterest-xml-catalog
**PHP Library for generating Pinterest Catalogs in XML (RSS 2.0) format**

This can be used to generate a [data source](https://help.pinterest.com/en/business/article/data-source-ingestion) for daily product ingestion by Pinterest. You must already have a [business account](https://help.pinterest.com/en/business/article/get-a-business-account) and a [claimed website](https://help.pinterest.com/en/business/article/claim-your-website) that meets their [merchant guidelines](https://policy.pinterest.com/en/merchant-guidelines).

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
$product->price = "14.99 EUR";

// Add products to catalog (can be ProductData, other object, or data array)
$xmlCatalog->addProduct($product);

// Convert to XML and serve as response
echo $xmlCatalog->toXmlString();

```

### Product data
Pinterest uses the Google Merchant Data [specifications](https://support.google.com/merchants/answer/7052112?hl=en) for RSS 2.0, which you can refer to for documentation on the specific fields used.

👉 This library only targets fields that the Pinterest samples/docs include.

Using the `ProductData` class is recommended because it contains relevant phpdocs to make your life easier. You can also pass your own associative array or object for each product to `addProduct()` if you prefer.

If you are adding products from your own object or array, you should make sure that the property names / array keys can be understood by the library. For example, if you want to set the Google Product Category, valid keys to set it are `googleProductCategory`, `g:google_product_category` and `google_product_category`.

### Required fields
When filling products, these fields are required:

| Key                 | Element               | Description                                                                                                                                    |
|---------------------|-----------------------|------------------------------------------------------------------------------------------------------------------------------------------------|
| `id`                | `g:id`                | Product ID. Always required.                                                                                                                   |
| `title`             | `title`               | Product name. Always required.                                                                                                                 |
| `description`       | `description`         | Product description. Always required.                                                                                                          |
| `link`              | `link`                | Product URL. Always required.                                                                                                                  |
| `image_link`        | `g:image_link`        | Primary image URL. Always required.                                                                                                            |
| `availability`      | `g:availability`      | Product availability. Always required. Defaults to `In stock`.                                                                                 |
| `availability_date` | `g:availability_date` | The date a preordered or backordered product becomes available for delivery. Required if product availability is set to preorder or backorder. |
| `price`             | `g:price`             | ISO 4217 price specification. Always required.                                                                                                 |
| `brand`             | `g:brand`             | Product brand name. Required except for movies, books, and musical recording brands.                                                           |
| `gtin`              | `g:gtin`              | Product GTIN. Required if available.                                                                                                           |
| `mpn`               | `g:mpn`               | Manufacturer Part Number (MPN). Required if your product does not have a manufacturer assigned GTIN.                                           |
| `condition`         | `g:condition`         | Product condition. Always required. Defaults to `New`.                                                                                         |

Note: Additional fields may be required depending on your target market(s) and their regulations. Refer to [Google Merchant Data](https://support.google.com/merchants/answer/7052112?hl=en) for details.