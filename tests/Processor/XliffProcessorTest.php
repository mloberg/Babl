<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Processor;

use Mlo\Babl\Processor\XliffProcessor;

/**
 * @coversDefaultClass \Mlo\Babl\Processor\XliffProcessor
 */
class XliffProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var XliffProcessor
     */
    private $processor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->processor = new XliffProcessor();
    }

    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $data = $this->processor->process(__DIR__ . '/../data/messages.en.xliff');

        $this->assertInternalType('array', $data);
        $this->assertEquals('bar', $data['foo']);
    }

    /**
     * @covers ::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->processor->supports('xliff'));
        $this->assertTrue($this->processor->supports('XLIFF'));
        $this->assertTrue($this->processor->supports('xlf'));
        $this->assertTrue($this->processor->supports('XLF'));
        $this->assertFalse($this->processor->supports('yaml'));
        $this->assertFalse($this->processor->supports('yml'));
    }
}
