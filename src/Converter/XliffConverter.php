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
    public function convert(array $data)
    {
        $doc = new \DOMDocument('1.0', 'utf-8');

        $doc->formatOutput = true;
        $doc->preserveWhiteSpace = true;

        $root = $doc->createElement('xliff');
        $root->setAttribute('version', '1.2');
        $root->setAttribute('xmlns', 'urn:oasis:names:tc:xliff:document:1.2');

        $file = $doc->createElement('file');
        $file->setAttribute('source-language', 'en');
        $file->setAttribute('datatype', 'plaintext');
        $file->setAttribute('original', 'file.ext');

        $body = $doc->createElement('body');

        $file->appendChild($body);
        $root->appendChild($file);
        $doc->appendChild($root);

        foreach ($data as $key => $value) {
            $source = $doc->createElement('source');

            if (preg_match('/[<&]/', $key)) {
                $sourceText = $doc->createCDATASection($key);
            } else {
                $sourceText = $doc->createTextNode($key);
            }

            $source->appendChild($sourceText);

            $target = $doc->createElement('target');

            if (preg_match('/[<&]/', $value)) {
                $targetText = $doc->createCDATASection($value);
            } else {
                $targetText = $doc->createTextNode($value);
            }

            $target->appendChild($targetText);

            $transUnit = $doc->createElement('trans-unit');
            $transUnit->setAttribute('id', $key);
            $transUnit->appendChild($source);
            $transUnit->appendChild($target);

            $body->appendChild($transUnit);
        }

        $xml = $doc->saveXML();

        // replace auto-closed tags
        $xml = str_replace('<target/>', '<target></target>', $xml);

        return $xml;
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
