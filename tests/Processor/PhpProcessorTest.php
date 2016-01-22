<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Processor;

use Mlo\Babl\Processor\PhpProcessor;

/**
 * @coversDefaultClass \Mlo\Babl\Processor\PhpProcessor
 */
class PhpProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpProcessor
     */
    private $processor;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->processor = new PhpProcessor();
    }

    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $data = $this->processor->process(__DIR__ . '/../data/messages.en.php');

        $this->assertInstanceOf('Traversable', $data);
        $this->assertEquals('bar', $data['foo']);
    }

    /**
     * @covers ::process
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /^File "[\w\/\.]+\/data\/invalid.en.php" did not return an array\.$/
     */
    public function testProcessThrowsExceptionOnInvalidData()
    {
        $this->processor->process(__DIR__ . '/../data/invalid.en.php');
    }

    /**
     * @covers ::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->processor->supports('php'));
        $this->assertTrue($this->processor->supports('PHP'));
        $this->assertFalse($this->processor->supports('yaml'));
        $this->assertFalse($this->processor->supports('yml'));
    }
}
