<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Converter;

use Symfony\Component\Yaml\Yaml;

/**
 * YamlConverter
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class YamlConverter implements ConverterInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * {@inheritdoc}
     */
    public function add($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent()
    {
        return YAML::dump($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return 'yml';
    }
}
