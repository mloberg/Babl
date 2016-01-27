<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Command;

use Mlo\Babl\Command\ConvertCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \Mlo\Babl\Command\ConvertCommand
 * @covers ::configure
 */
class ConvertCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConvertCommand
     */
    private $command;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * @var string
     */
    private $testDir;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->testDir = __DIR__ . '/../data/translations';

        $application = new Application();
        $application->add(new ConvertCommand());

        $this->command = $application->find('convert');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $targetFile = $this->testDir . '/validations.en.yml';
        @unlink($targetFile);

        $this->assertFalse(file_exists($targetFile));

        $this->commandTester->execute([
            'command'  => $this->command->getName(),
            'file'     => $this->testDir . '/validations.en.xliff',
            '--format' => 'yml',
        ]);

        $expectedDisplay = sprintf('File converted to %s.', $targetFile);
        $expectedContent = <<<EOF
text.min_length: 'Text is not long enough'
text.max_length: 'Text is too long'
text.email: 'Text is not a valid email'

EOF;

        $this->assertEquals($expectedDisplay, trim($this->commandTester->getDisplay()));
        $this->assertTrue(file_exists($targetFile));
        $this->assertEquals($expectedContent, file_get_contents($targetFile));
    }

    /**
     * @covers ::execute
     */
    public function testExecuteDefaultFormatXliff()
    {
        $targetFile = $this->testDir . '/messages.en.xliff';
        @unlink($targetFile);

        $this->assertFalse(file_exists($targetFile));

        $this->commandTester->execute([
            'command' => $this->command->getName(),
            'file'    => $this->testDir . '/messages.en.yml',
        ]);

        $expectedDisplay = sprintf('File converted to %s.', $targetFile);
        $expectedContent = <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<xliff xmlns="urn:oasis:names:tc:xliff:document:1.2" version="1.2">
  <file source-language="en" target-language="en" datatype="plaintext" original="messages.en.yml">
    <body>
      <trans-unit id="greeting">
        <source>greeting</source>
        <target>Hello World!</target>
      </trans-unit>
      <trans-unit id="name">
        <source>name</source>
        <target>John Doe</target>
      </trans-unit>
    </body>
  </file>
</xliff>

EOF;


        $this->assertEquals($expectedDisplay, trim($this->commandTester->getDisplay()));
        $this->assertTrue(file_exists($targetFile));
        $this->assertEquals($expectedContent, file_get_contents($targetFile));
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
        ]);
    }

    /**
     * @covers ::execute
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unknown format "txt".
     */
    public function testExecuteWillThrowExceptionWhenConverterCannotBeFound()
    {
        $this->commandTester->execute([
            'command'  => $this->command->getName(),
            'file'     => $this->testDir . '/messages.en.yml',
            '--format' => 'txt',
        ]);
    }

    /**
     * @covers ::execute
     */
    public function testExecuteWillNotOverwriteFileWithoutAsking()
    {
        $targetFile = $this->testDir . '/messages.en.php';

        $this->assertTrue(file_exists($targetFile));

        $targetFileUpdatedTime = filemtime($targetFile);

        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', ['ask']);
        $question->expects($this->once())->method('ask')->will($this->returnValue(false));
        $this->command->getHelperSet()->set($question, 'question');

        $this->commandTester->execute([
            'command'  => $this->command->getName(),
            'file'     => $this->testDir . '/messages.en.yml',
            '--format' => 'php',
        ]);

        $this->assertEmpty("", $this->commandTester->getDisplay());
        $this->assertEquals($targetFileUpdatedTime, filemtime($targetFile));
    }
}
