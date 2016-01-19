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
     * @inheritDoc
     */
    public function convert(\Traversable $data, $name = 'messages', $lang = 'en')
    {
        return Yaml::dump((array) $data);
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return in_array(strtolower($format), ['yml', 'yaml']);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return 'yml';
    }
}
