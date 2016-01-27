<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Converter;

use Mlo\Babl\Converter\YamlConverter;

/**
 * @coversDefaultClass \Mlo\Babl\Converter\YamlConverter
 */
class YamlConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YamlConverter
     */
    private $converter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->converter = new YamlConverter();
    }

    /**
     * @covers ::convert
     */
    public function testConvert()
    {
        $data = ['foo' => 'bar'];
        $expected = <<<EOF
foo: bar

EOF;

        $this->assertEquals($expected, $this->converter->convert(new \ArrayIterator($data)));
    }

    /**
     * @covers ::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->converter->supports('yaml'));
        $this->assertTrue($this->converter->supports('YAML'));
        $this->assertTrue($this->converter->supports('yml'));
        $this->assertTrue($this->converter->supports('YML'));
        $this->assertFalse($this->converter->supports('xliff'));
        $this->assertFalse($this->converter->supports('xlf'));
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtension()
    {
        $this->assertEquals('yml', $this->converter->getExtension());
    }
}
