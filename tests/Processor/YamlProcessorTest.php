<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Processor;

use Mlo\Babl\Processor\YamlProcessor;

/**
 * @coversDefaultClass \Mlo\Babl\Processor\YamlProcessor
 */
class YamlProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YamlProcessor
     */
    private $processor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->processor = new YamlProcessor();
    }

    /**
     * @covers ::process
     */
    public function testProcess()
    {
        $data = $this->processor->process(__DIR__ . '/../data/messages.en.yml');

        $this->assertInternalType('array', $data);
        $this->assertEquals('bar', $data['foo']);
    }

    /**
     * @covers ::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->processor->supports('yaml'));
        $this->assertTrue($this->processor->supports('YAML'));
        $this->assertTrue($this->processor->supports('yml'));
        $this->assertTrue($this->processor->supports('YML'));
        $this->assertFalse($this->processor->supports('php'));
        $this->assertFalse($this->processor->supports('xliff'));
    }
}
