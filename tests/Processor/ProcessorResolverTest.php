<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Processor;

use Mlo\Babl\Processor\ProcessorResolver;
use Mlo\Babl\Tests\Mock\ProcessorMock;

/**
 * @coversDefaultClass \Mlo\Babl\Processor\ProcessorResolver
 * @covers ::__construct
 */
class ProcessorResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProcessorResolver
     */
    private $resolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->resolver = new ProcessorResolver([new ProcessorMock('php')]);
    }

    /**
     * @covers ::addProcessor
     * @covers ::getProcessors
     */
    public function testAddGetProcessors()
    {
        $this->assertCount(1, $this->resolver->getProcessors());
        $this->assertFalse($this->resolver->getProcessors()[0]->supports('yml'));
        $this->assertSame($this->resolver, $this->resolver->addProcessor(new ProcessorMock('yml')));
        $this->assertCount(2, $this->resolver->getProcessors());
        $this->assertFalse($this->resolver->getProcessors()[0]->supports('yml'));
        $this->assertTrue($this->resolver->getProcessors()[1]->supports('yml'));
    }

    /**
     * @covers ::resolve
     */
    public function testResolve()
    {
        $processor = new ProcessorMock('xliff');

        $this->assertFalse($this->resolver->resolve('xliff'));
        $this->resolver->addProcessor($processor);
        $this->assertSame($processor, $this->resolver->resolve('xliff'));
    }
}
