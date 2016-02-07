<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Utility;

use Mlo\Babl\Utility\FileInfo;

/**
 * @coversDefaultClass \Mlo\Babl\Utility\FileInfo
 * @covers ::__construct
 */
class FileInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->fileInfo = new FileInfo('translations/messages.foo.en.yml');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage File "foo.txt" did not match the format [name].[lang].[ext].
     */
    public function testThrowsExceptionOnInvalidFile()
    {
        new FileInfo('foo.txt');
    }

    /**
     * @covers ::getFile
     */
    public function testGetFile()
    {
        $this->assertEquals('translations/messages.foo.en.yml', $this->fileInfo->getFile());
    }

    /**
     * @covers ::getFilename
     */
    public function testGetFilename()
    {
        $this->assertEquals('messages.foo.en.yml', $this->fileInfo->getFilename());
    }

    /**
     * @covers ::getDirectory
     */
    public function testGetDirectory()
    {
        $this->assertEquals('translations', $this->fileInfo->getDirectory());
    }

    /**
     * @covers ::getExtension
     */
    public function testGetExtension()
    {
        $this->assertEquals('yml', $this->fileInfo->getExtension());
    }

    /**
     * @covers ::getLanguage
     */
    public function testGetLanguage()
    {
        $this->assertEquals('en', $this->fileInfo->getLanguage());
    }

    /**
     * @covers ::getName
     */
    public function testGetName()
    {
        $this->assertEquals('messages.foo', $this->fileInfo->getName());
    }
}
