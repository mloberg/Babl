<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Processor;

/**
 * ProcessorInterface
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
interface ProcessorInterface
{
    /**
     * Process translation file
     *
     * @param string $file
     *
     * @return \Traversable
     */
    public function process($file);

    /**
     * Returns whether this class supports the given format
     *
     * @param string $format
     *
     * @return bool
     */
    public function supports($format);
}
