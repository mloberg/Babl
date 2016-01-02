<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Processor;

use Symfony\Component\Yaml\Yaml;

/**
 * YamlProcessor
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class YamlProcessor implements \IteratorAggregate
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        $data = Yaml::parse(file_get_contents($this->filename));
        return new \ArrayIterator($data);
    }
}
