<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Converter;

/**
 * PhpConverter
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class PhpConverter implements ConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convert(\Traversable $data, $source = null, $name = 'messages', $lang = 'en')
    {
        return sprintf("<?php\n\nreturn %s;", var_export((array) $data, true));
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return $this->getExtension() === strtolower($format);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return 'php';
    }
}
