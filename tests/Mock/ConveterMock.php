<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Mock;

use Mlo\Babl\Converter\ConverterInterface;

class ConverterMock implements ConverterInterface
{
    /**
     * @var bool
     */
    private $support;

    /**
     * @var string
     */
    private $extension;

    /**
     * Constructor
     *
     * @param bool $support
     */
    public function __construct($support = false, $extension = 'yml')
    {
        $this->support   = $support;
        $this->extension = $extension;
    }

    /**
     * @inheritDoc
     */
    public function convert(\Traversable $data)
    {
        // TODO: Implement convert() method.
    }

    /**
     * @inheritDoc
     */
    public function supports($format)
    {
        return $this->support;
    }

    /**
     * @inheritDoc
     */
    public function getExtension()
    {
        return $this->extension;
    }
}
