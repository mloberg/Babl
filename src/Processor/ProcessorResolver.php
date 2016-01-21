<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Processor;

/**
 * ProcessorResolver
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class ProcessorResolver
{
    /**
     * @var ProcessorInterface[]|array
     */
    private $processors;

    /**
     * Constructor
     *
     * @param array|ProcessorInterface[] $processors
     */
    public function __construct(array $processors = [])
    {
        $this->processors = [];

        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * Returns a processor that supports given extension
     *
     * @param string $format
     *
     * @return ProcessorInterface|bool false if no supported processor found
     */
    public function resolve($format)
    {
        foreach ($this->processors as $processor) {
            if ($processor->supports($format)) {
                return $processor;
            }
        }

        return false;
    }

    /**
     * Add processor to resolver
     *
     * @param ProcessorInterface $processor
     *
     * @return ProcessorResolver
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;

        return $this;
    }

    /**
     * Returns the registered processors
     *
     * @return array|ProcessorInterface[]
     */
    public function getProcessors()
    {
        return $this->processors;
    }
}
