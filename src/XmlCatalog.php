<?php

namespace SoftwarePunt\PinterestXmlCatalog;

use SoftwarePunt\PinterestXmlCatalog\Models\BaseModel;
use SoftwarePunt\PinterestXmlCatalog\Models\ProductData;

class XmlCatalog
{
    /**
     * @var ProductData[]
     */
    private array $products;

    public function __construct()
    {
        $this->products = [];
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Data

    /**
     * Adds a product to the catalog.
     *
     * @param array|object|ProductData $product Object or array containing product data.
     * @return void
     */
    public function addProduct($product)
    {
        if ($product === null)
            throw new \InvalidArgumentException("\$product should not be null");

        $this->products[] = ProductData::createFrom($product);
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Render

    /**
     * Renders the catalog into a DOMDocument.
     */
    public function toDomDocument(): \DOMDocument
    {
        $document = new \DOMDocument('1.0', 'utf-8');

        $rssRoot = $document->createElement('rss');
        $rssRoot->setAttribute('version', '2.0');
        $rssRoot->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

        $channelRoot = $document->createElement('channel');

        foreach ($this->products as $product) {
            $this->writeModel($product, $document, $channelRoot, "item");
        }

        $rssRoot->appendChild($channelRoot);
        $document->appendChild($rssRoot);
        return $document;
    }

    private function writeModel(BaseModel $model, \DOMDocument $document, \DOMElement $parent, string $elementName)
    {
        $itemElement = $document->createElement($elementName);

        foreach ($model->iteratePropertyToElementMap() as $propertyName => $subElementName) {
            if (empty($model->$propertyName))
                continue;

            $value = $model->$propertyName;

            if (is_array($value)) {
                foreach ($value as $subValue) {
                    $subElement = $document->createElement($subElementName);
                    $subElement->textContent = strval($subValue);
                    $itemElement->appendChild($subElement);
                }
            } else if ($value instanceof \DateTime) {
                $subElement = $document->createElement($subElementName);
                $subElement->textContent = str_replace('+00:00', 'Z', $value->format('c'));
                $itemElement->appendChild($subElement);
            } else {
                $subElement = $document->createElement($subElementName);
                $subElement->textContent = strval($value);
                $itemElement->appendChild($subElement);
            }


        }

        $parent->appendChild($itemElement);
    }

    /**
     * Renders the catalog into an XML string.
     */
    public function toXmlString(bool $preserveWhiteSpace = false, bool $formatOutput = true): string
    {
        $document = $this->toDomDocument();
        $document->preserveWhiteSpace = $preserveWhiteSpace;
        $document->formatOutput = $formatOutput;
        return $document->saveXML();
    }
}