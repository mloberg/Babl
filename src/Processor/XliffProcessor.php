<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Processor;

/**
 * XliffProcessor
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class XliffProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($file)
    {
        $doc = new \DOMDocument();
        $doc->loadXML(file_get_contents($file));

        $data = [];

        /** @var \DOMElement $el */
        foreach ($doc->getElementsByTagName("trans-unit") as $el) {
            $key = $el->getElementsByTagName('source')->item(0)->textContent;
            $value = $el->getElementsByTagName('target')->item(0)->textContent;

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($format)
    {
        return in_array(strtolower($format), ['xliff', 'xlf']);
    }
}
