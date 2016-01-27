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
class YamlProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($file)
    {
        $data = Yaml::parse(file_get_contents($file));

        return new \ArrayIterator($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($format)
    {
        return in_array(strtolower($format), ['yml', 'yaml']);
    }
}
