<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Command;

use Mlo\Babl\Command\AddCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Yaml\Yaml;

/**
 * @coversDefaultClass \Mlo\Babl\Command\AddCommand
 * @covers ::configure
 */
class AddCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AddCommand
     */
    private $command;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var string
     */
    private $testFile;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->testFile = __DIR__ . '/../data/translations/test.en.yml';

        @unlink($this->testFile);

        copy(__DIR__ . '/../data/translations/messages.en.yml', $this->testFile);

        $application = new Application();
        $application->add(new AddCommand());

        $this->command = $application->find('add');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $values = Yaml::parse(file_get_contents($this->testFile));

        $this->assertArrayNotHasKey('foo', $values);

        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'file'    => $this->testFile,
            'key'     => 'foo',
            'value'   => 'bar',
        ]);

        $expectedDisplay = sprintf('Translation key "foo" added to "%s".', $this->testFile);
        $values = Yaml::parse(file_get_contents($this->testFile));

        $this->assertEquals($expectedDisplay, trim($this->commandTester->getDisplay()));
        $this->assertEquals('bar', $values['foo']);
    }

    /**
     * @covers ::execute
     */
    public function testExecuteWillPromptForValueIfNoneWasGiven()
    {
        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', ['ask']);
        $question->expects($this->once())->method('ask')->will($this->returnValue('bar'));
        $this->command->getHelperSet()->set($question, 'question');

        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'file'    => $this->testFile,
            'key'     => 'foo',
        ]);

        $expectedDisplay = sprintf('Translation key "foo" added to "%s".', $this->testFile);
        $values = Yaml::parse(file_get_contents($this->testFile));

        $this->assertEquals($expectedDisplay, trim($this->commandTester->getDisplay()));
        $this->assertEquals('bar', $values['foo']);
    }

    /**
     * @covers ::execute
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unknown extension "txt".
     */
    public function testExecuteWillThrowExceptionWhenProcessorCannotBeFound()
    {
        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'file'    => 'foobar.txt',
            'key'     => 'foo',
            'value'   => 'bar',
        ]);
    }

    /**
     * @covers ::execute
     */
    public function testExecuteWillNotOverwriteKeyWithoutAsking()
    {
        $values = Yaml::parse(file_get_contents($this->testFile));

        $this->assertEquals('John Doe', $values['name']);

        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', ['ask']);
        $question->expects($this->once())->method('ask')->will($this->returnValue(false));
        $this->command->getHelperSet()->set($question, 'question');

        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'file'    => $this->testFile,
            'key'     => 'name',
            'value'   => 'Matthew Loberg',
        ]);

        $values = Yaml::parse(file_get_contents($this->testFile));

        $this->assertEmpty("", $this->commandTester->getDisplay());
        $this->assertEquals('John Doe', $values['name']);
    }
}
