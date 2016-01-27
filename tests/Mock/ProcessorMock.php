<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Mock;

use Mlo\Babl\Processor\ProcessorInterface;

class ProcessorMock implements ProcessorInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * Constructor
     *
     * @param string $format
     */
    public function __construct($format = 'php')
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function process($file)
    {
        // TODO: Implement process() method.
    }

    /**
     * {@inheritdoc}
     */
    public function supports($format)
    {
        return $format === $this->format;
    }
}
