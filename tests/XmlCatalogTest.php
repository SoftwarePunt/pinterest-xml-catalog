<?php

use PHPUnit\Framework\TestCase;
use SoftwarePunt\PinterestXmlCatalog\Enums\ProductAvailability;
use SoftwarePunt\PinterestXmlCatalog\Models\ProductData;
use SoftwarePunt\PinterestXmlCatalog\XmlCatalog;

class XmlCatalogTest extends TestCase
{
    private static function normalizeLineEndings(string $text)
    {
        $text = str_replace("\r", "", $text);
        $text = str_replace("\n", PHP_EOL, $text);
        return trim($text);
    }

    private function assertSameXml(string $expected, string $actual,
                                   string $message = "Output XML should match expectation")
    {
        return $this->assertSame(
            self::normalizeLineEndings($expected),
            self::normalizeLineEndings($actual),
            $message
        );
    }

    // -----------------------------------------------------------------------------------------------------------------

    public function testEmptyDocument()
    {
        $xmlCatalog = new XmlCatalog();

        $expected = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
  <channel/>
</rss>
EOD;
        $actual = $xmlCatalog->toXmlString();

        $this->assertSameXml($expected, $actual, "Empty document should be formatted RSS with no items");
    }

    public function testDocumentWithProduct()
    {
        $product = new ProductData();
        $product->id = "4000086";
        $product->title = "Illuminating Makeup Mirror";
        $product->description = "A ring light mirror with 23 LED strip lights.";
        $product->link = "https://www.example.com/cat/illuminating-makeup-mirror";
        $product->imageLink = "https://www.example.com/media/catalog/product/image.jpg";
        $product->additionalImageLink = "https://www.example.com/media/catalog/product/image_side.jpg";
        $product->availability = ProductAvailability::Preorder;
        $product->availabilityDate = new DateTime('tomorrow');
        $product->price = "14.99 EUR";
        $product->salePrice = "10.99 EUR";

        $xmlCatalog = new XmlCatalog();
        $xmlCatalog->addProduct($product);

        $expected = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
  <channel>
    <item>
      <g:id>4000086</g:id>
      <title>Illuminating Makeup Mirror</title>
      <description>A ring light mirror with 23 LED strip lights.</description>
      <link>https://www.example.com/cat/illuminating-makeup-mirror</link>
      <g:image_link>https://www.example.com/media/catalog/product/image.jpg</g:image_link>
      <g:additional_image_link>https://www.example.com/media/catalog/product/image_side.jpg</g:additional_image_link>
      <g:availability>Preorder</g:availability>
      <g:availability_date>{$product->availabilityDate->format('Y-m-d')}T00:00:00Z</g:availability_date>
      <g:price>14.99 EUR</g:price>
      <sale_price>10.99 EUR</sale_price>
      <g:condition>New</g:condition>
    </item>
  </channel>
</rss>
EOD;
        $actual = $xmlCatalog->toXmlString();

        $this->assertSameXml($expected, $actual, "Document with product should be correctly formatted RSS feed");
    }


    public function testProductWithMultipleAdditionalImages()
    {
        $product = new ProductData();
        $product->id = "4000086";
        $product->title = "Illuminating Makeup Mirror";
        $product->description = "A ring light mirror with 23 LED strip lights.";
        $product->link = "https://www.example.com/cat/illuminating-makeup-mirror";
        $product->imageLink = "https://www.example.com/media/catalog/product/image.jpg";
        $product->additionalImageLink[] = "https://www.example.com/media/catalog/product/image_side_a.jpg";
        $product->additionalImageLink[] = "https://www.example.com/media/catalog/product/image_side_b.jpg";
        $product->additionalImageLink[] = "https://www.example.com/media/catalog/product/image_side_c.jpg";

        $xmlCatalog = new XmlCatalog();
        $xmlCatalog->addProduct($product);

        $expected = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">
  <channel>
    <item>
      <g:id>4000086</g:id>
      <title>Illuminating Makeup Mirror</title>
      <description>A ring light mirror with 23 LED strip lights.</description>
      <link>https://www.example.com/cat/illuminating-makeup-mirror</link>
      <g:image_link>https://www.example.com/media/catalog/product/image.jpg</g:image_link>
      <g:additional_image_link>https://www.example.com/media/catalog/product/image_side_a.jpg</g:additional_image_link>
      <g:additional_image_link>https://www.example.com/media/catalog/product/image_side_b.jpg</g:additional_image_link>
      <g:additional_image_link>https://www.example.com/media/catalog/product/image_side_c.jpg</g:additional_image_link>
      <g:availability>In Stock</g:availability>
      <g:condition>New</g:condition>
    </item>
  </channel>
</rss>
EOD;
        $actual = $xmlCatalog->toXmlString();

        $this->assertSameXml($expected, $actual, "Document with product should be correctly formatted RSS feed");
    }
}