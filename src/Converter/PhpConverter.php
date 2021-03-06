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
     * {@inheritdoc}
     */
    public function convert(array $data)
    {
        return sprintf("<?php\n\nreturn %s;\n", var_export($data, true));
    }

    /**
     * {@inheritdoc}
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
