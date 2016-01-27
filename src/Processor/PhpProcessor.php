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
class PhpProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($file)
    {
        $data = require($file);

        if (!is_array($data)) {
            throw new \RuntimeException(sprintf('File "%s" did not return an array.', $file));
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($format)
    {
        return 'php' === strtolower($format);
    }
}
