<?php

class HTMLParser {
    private $document;

    public function __construct($htmlString, $preserveWhiteSpace = false, $formatOutput = false) {
        $this->document = new DOMDocument;
        $this->document->preserveWhiteSpace = $preserveWhiteSpace;
        $this->document->formatOutput = $formatOutput;
        $this->document->loadHTML($htmlString);
    }

    public function getElementsByTagName($tagName) {
        $result = array();

        $elements = $this->document->getElementsByTagName($tagName);

        foreach ($elements as $element) {
            array_push($result, $element);
        }

        return $result;
    }

    public function getElementsByTagAndClassName($tagName, $className) {
        $result = array();

        $elements = $this->document->getElementsByTagName($tagName);

        foreach ($elements as $element) {
            $currentClassName = HTMLParser::getElementAttribute($element, 'class');

            if ($currentClassName === $className) {
                array_push($result, $element);
            }
        }

        return $result;
    }

    public function getElementById($id) {
        return $this->document->getElementById($id);
    }

    public static function getElementAttribute($element, $attributeName){
        foreach($element->attributes as $attribute) {
            if ($attribute->name === $attributeName) {
                return $attribute->value;
            }
        }

        return NULL;
    }

    public static function getElementText ($element) {
        return $element->textContent;
    }

    public static function getElementHTML ($element) {
        return $element->ownerDocument->saveHTML($element);
    }
}
