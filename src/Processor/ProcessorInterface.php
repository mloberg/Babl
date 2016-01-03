<?php

namespace Mlo\Babl\Processor;

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
