<?php

class HTMLDocument extends DOMDocument {
    public function loadHTML($source, $options = 0, $preserveWhiteSpace = false, $formatOutput = false)
    {
        $original_error_reporting = error_reporting();
        error_reporting(0);

        $this->preserveWhiteSpace = $preserveWhiteSpace;
        $this->formatOutput = $formatOutput;
        parent::loadHTML(mb_convert_encoding($source, 'HTML-ENTITIES', 'UTF-8'), $options);

        error_reporting($original_error_reporting);
    }

    public function find($selector) {
        $selectors = $this->destructSelector($selector);

    }

    public function getHtml() {
        return $this->ownerDocument->saveHTML($this);
    }

    public function getText($removeAdditionalWhitespaces = true) {
        if ($removeAdditionalWhitespaces) {
            return $this->removeAdditionalWhitespaces($this->textContent);
        } else {
            return $this->textContent;
        }
    }

    private function destructSelector($selector) {
        $result = array();
        $selectors = explode(' ', $selector);

        foreach ($selectors as $singleSelector) {
            $currentSelectorFragment = '';
            $selectorSymbols = explode('', $singleSelector);

            foreach ($selectorSymbols as $selectorSymbol) {
                if (in_array($selectorSymbol, array('#', '.', '&'))) {
                    if (mb_strlen($currentSelectorFragment, 'UTF-8')) {
                        array_push($result, $currentSelectorFragment);
                        $currentSelectorFragment = $selectorSymbol;
                    } else {
                        $currentSelectorFragment = $selectorSymbol;
                    }
                } else {
                    $currentSelectorFragment .= $selectorSymbol;
                }
            }
        }
    }

    private function removeAdditionalWhitespaces($string) {
        $trimmedString = trim($string);
        $stringWithSingleSpaces = preg_replace('/ +/', ' ', $trimmedString);
        $stringWithSingleNewLines = preg_replace("\n+", "\n", $stringWithSingleSpaces);

        return $stringWithSingleNewLines;
    }
}
