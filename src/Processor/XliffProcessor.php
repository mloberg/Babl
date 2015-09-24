<?php
/**
 * XliffProcessor.php
 *
 * @package Mlo\Babl
 */

namespace Mlo\Babl\Processor;

/**
 * XliffProcessor
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class XliffProcessor implements \IteratorAggregate
{
    /**
     * @var \DOMDocument
     */
    private $doc;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $doc = new \DOMDocument();
        $doc->loadXML(file_get_contents($filename));

        $this->doc = $doc;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $data = [];

        /** @var \DOMElement $el */
        foreach ($this->doc->getElementsByTagName("trans-unit") as $el) {
            $key = $el->getElementsByTagName('source')->item(0)->textContent;
            $value = $el->getElementsByTagName('target')->item(0)->textContent;

            $data[$key] = $value;
        }

        return new \ArrayIterator($data);
    }
}
