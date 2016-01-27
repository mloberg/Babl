<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Converter;

use Mlo\Babl\Converter\XliffConverter;

/**
 * @coversDefaultClass \Mlo\Babl\Converter\XliffConverter
 */
class XliffConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var XliffConverter
     */
    private $converter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->converter = new XliffConverter();
    }

    /**
     * @covers ::convert
     */
    public function testConvert()
    {
        $data = ['foo' => 'bar'];
        $expected = <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<xliff xmlns="urn:oasis:names:tc:xliff:document:1.2" version="1.2">
  <file source-language="en" target-language="en" datatype="plaintext" original="messages.en.xliff">
    <body>
      <trans-unit id="foo">
        <source>foo</source>
        <target>bar</target>
      </trans-unit>
    </body>
  </file>
</xliff>

EOF;

        $this->assertEquals($expected, $this->converter->convert($data, 'messages.en.xliff'));
    }

    /**
     * @covers ::supports
     */
    public function testSupports()
    {
        $this->assertTrue($this->converter->supports('xliff'));
        $this->assertTrue($this->converter->supports('XLIFF'));
        $this->assertTrue($this->converter->supports('xlf'));
        $this->assertTrue($this->converter->supports('XLF'));
        $this->assertFalse($this->converter->supports('yaml'));
        $this->assertFalse($this->converter->supports('yml'));
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtension()
    {
        $this->assertEquals('xliff', $this->converter->getExtension());
    }
}
