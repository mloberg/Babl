<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Converter;

/**
 * XliffConverter
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class XliffConverter implements ConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function convert(array $data, $source = null, $name = 'messages', $lang = 'en')
    {
        $dom = new \DOMDocument('1.0');
        $dom->formatOutput = true;
        $dom->encoding = 'utf-8';
        $dom->preserveWhiteSpace = true;
        $rootEl = $dom->createElement('xliff');
        $rootEl->setAttribute('version', '1.2');
        $rootEl->setAttribute('xmlns', 'urn:oasis:names:tc:xliff:document:1.2');
        $dom->appendChild($rootEl);

        $fileEl = $dom->createElement('file');
        $fileEl->setAttribute('source-language', $lang);
        $fileEl->setAttribute('target-language', $lang);
        $fileEl->setAttribute('datatype', 'plaintext');

        if ($source) {
            $fileEl->setAttribute('original', $source);
        }

        $bodyEl = $dom->createElement('body');
        $fileEl->appendChild($bodyEl);
        $rootEl->appendChild($fileEl);

        foreach ($data as $key => $value) {
            $transUnitEl = $dom->createElement('trans-unit');
            $transUnitEl->setAttribute('id', $key);
            $sourceEl = $dom->createElement('source');
            $sourceEl->nodeValue = $key;
            $targetEl = $dom->createElement('target');
            $targetEl->nodeValue = trim($value);
            $transUnitEl->appendChild($sourceEl);
            $transUnitEl->appendChild($targetEl);
            $bodyEl->appendChild($transUnitEl);
        }

        return $dom->saveXml();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($format)
    {
        return in_array(strtolower($format), ['xliff', 'xlf']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return 'xliff';
    }
}
