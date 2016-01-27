<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Converter;

use Mlo\Babl\Converter\PhpConverter;

/**
 * @coversDefaultClass \Mlo\Babl\Converter\PhpConverter
 */
class PhpConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PhpConverter
     */
    private $converter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->converter = new PhpConverter();
    }

    /**
     * @covers ::convert
     */
    public function testConvert()
    {
        $data = ['foo' => 'bar'];
        $expected = <<<EOF
<?php

return array (
  'foo' => 'bar',
);
EOF;

        $this->assertEquals($expected, $this->converter->convert(new \ArrayIterator($data)));
    }

    /**
     * @covers ::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->converter->supports('php'));
        $this->assertTrue($this->converter->supports('PHP'));
        $this->assertFalse($this->converter->supports('yaml'));
        $this->assertFalse($this->converter->supports('yml'));
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtension()
    {
        $this->assertEquals('php', $this->converter->getExtension());
    }
}
