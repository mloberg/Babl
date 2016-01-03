<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Converter;

/**
 * ConverterInterface
 *
 * @author  Matthew Loberg <loberg.matt@gmail.com>
 */
interface ConverterInterface
{
    /**
     * Convert translation data
     *
     * @param \Traversable $data Translation data
     *
     * @return string Processed content
     */
    public function convert(\Traversable $data);

    /**
     * Returns whether this class supports the given format
     *
     * @param string $format
     *
     * @return bool
     */
    public function supports($format);

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension();
}
