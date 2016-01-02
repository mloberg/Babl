<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Processor;

/**
 * PhpProcessor
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class PhpProcessor implements \IteratorAggregate
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->data = require($filename);

        if (!is_array($this->data)) {
            throw new \RuntimeException(sprintf("%s did not return an array", $filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
