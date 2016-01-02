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
        return sprintf("<?php\n\nreturn %s;", var_export($this->data, true));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension()
    {
        return 'php';
    }
}
