<?php

namespace SoftwarePunt\PinterestXmlCatalog\Models;

abstract class BaseModel
{
    // -----------------------------------------------------------------------------------------------------------------
    // Init

    public function __construct(array $data = null)
    {
        foreach ($this->iteratePropertyToElementMap() as $propName => $elementName) {
            $elementNameNoG = str_replace("g:", "", $elementName);
            $value = $data[$propName] ?? $data[$elementName]  ?? $data[$elementNameNoG] ?? null;
            if ($value !== null) {
                $this->$propName = $value;
            }
        }
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Reflection

    public function iteratePropertyKeys(): \Traversable
    {
        $reflectObj = new \ReflectionObject($this);

        foreach ($reflectObj->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty)
            yield $reflectionProperty->name;
    }

    public function iteratePropertyToElementMap(): \Traversable
    {
        $reflectObj = new \ReflectionObject($this);

        $expectedPrefix = "@element";
        $expectedPrefixLen = strlen($expectedPrefix);

        foreach ($reflectObj->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $propName = $reflectionProperty->name;
            $elementName = $propName;

            // Look for @element doc comment which will be the element name in RSS
            // If it's missing, assume the property name is also the element name

            $docComment = $reflectionProperty->getDocComment();

            if ($docComment) {
                $docLines = explode("\n", $docComment);

                foreach ($docLines as $docLine) {
                    $docLine = trim($docLine, " \t\n\r\0\x0B*");

                    if (strpos($docLine, $expectedPrefix) === 0) {
                        $elementName = trim(substr($docLine, $expectedPrefixLen));
                    }
                }
            }

            yield $propName => $elementName;
        }
    }
}