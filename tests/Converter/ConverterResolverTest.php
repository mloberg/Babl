<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Converter;

use Mlo\Babl\Converter\ConverterResolver;
use Mlo\Babl\Tests\Mock\ConverterMock;

/**
 * @coversDefaultClass \Mlo\Babl\Converter\ConverterResolver
 * @covers ::__construct
 */
class ConverterResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConverterResolver
     */
    private $resolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->resolver = new ConverterResolver([new ConverterMock()]);
    }

    /**
     * @covers ::addConverter
     * @covers ::getConverters
     */
    public function testAddGetConverters()
    {
        $this->assertCount(1, $this->resolver->getConverters());
        $this->assertFalse($this->resolver->getConverters()[0]->supports(''));
        $this->assertSame($this->resolver, $this->resolver->addConverter(new ConverterMock(true)));
        $this->assertCount(2, $this->resolver->getConverters());
        $this->assertFalse($this->resolver->getConverters()[0]->supports(''));
        $this->assertTrue($this->resolver->getConverters()[1]->supports(''));
    }

    /**
     * @covers ::resolve
     */
    public function testResolve()
    {
        $converter = new ConverterMock(true);

        $this->assertFalse($this->resolver->resolve(''));
        $this->resolver->addConverter($converter);
        $this->assertSame($converter, $this->resolver->resolve(''));
    }
}
