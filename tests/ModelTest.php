<?php

use PHPUnit\Framework\TestCase;
use SoftwarePunt\PinterestXmlCatalog\Models\ProductData;

class ModelTest extends TestCase
{
    private function createDummyProduct(): ProductData
    {
        return new ProductData(['id' => 123]);
    }

    public function testGetPropertyKeys()
    {
        $product = $this->createDummyProduct();
        $propertyKeys = iterator_to_array($product->iteratePropertyKeys());

        $this->assertContains("id", $propertyKeys);
    }

    public function testGetPropertyKeyToElementNameMap()
    {
        $product = $this->createDummyProduct();
        $map = iterator_to_array($product->iteratePropertyToElementMap());

        $this->assertSame("g:id", $map['id']);
    }
}
