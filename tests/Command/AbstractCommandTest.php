<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Command;

use Mlo\Babl\Command\AbstractCommand;
use Mlo\Babl\Tests\Command\Mock\CommandMock;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \Mlo\Babl\Command\AbstractCommand
 */
class AbstractCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractCommand
     */
    private $command;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $application = new Application();
        $application->add(new CommandMock());

        $this->command = $application->find('test');

        $commandTester = new CommandTester($this->command);
        $commandTester->execute(['command' => $this->command->getName()]);
    }

    /**
     * @covers ::initialize
     * @covers ::getProcessorResolver
     */
    public function testGetProcessorResolver()
    {
        $resolver = $this->command->getProcessorResolver();

        $this->assertInstanceOf('Mlo\Babl\Processor\ProcessorResolver', $resolver);
        $this->assertInstanceOf('Mlo\Babl\Processor\ProcessorInterface', $resolver->resolve('yaml'));
    }

    /**
     * @covers ::initialize
     * @covers ::getConverterResolver
     */
    public function testGetConverterResolver()
    {
        $resolver = $this->command->getConverterResolver();

        $this->assertInstanceOf('Mlo\Babl\Converter\ConverterResolver', $resolver);
        $this->assertInstanceOf('Mlo\Babl\Converter\ConverterInterface', $resolver->resolve('yaml'));
    }

    /**
     * @covers ::getFileInfo
     */
    public function testGetFileInfo()
    {
        $this->assertInstanceOf('Mlo\Babl\Utility\FileInfo', $this->command->getFileInfo('messages.en.yml'));
    }

    /**
     * @covers ::getProcessor
     */
    public function testGetProcessor()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @covers ::getProcessor
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown format "txt".
     */
    public function testGetProcessorThrowsExceptionOnUnknownFormat()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @covers ::getProcessorForFile
     */
    public function testGetProcessorForFile()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @covers ::getConverter
     */
    public function testGetConverter()
    {
        $this->markTestIncomplete('TODO');
    }

    /**
     * @covers ::getConverter
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown format "txt".
     */
    public function testGetConverterThrowsExceptionOnUnknownFormat()
    {
        $this->markTestIncomplete('TODO');
    }
}
