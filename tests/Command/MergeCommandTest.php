<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Tests\Command;

use Mlo\Babl\Command\MergeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @coversDefaultClass \Mlo\Babl\Command\MergeCommand
 * @covers ::configure
 */
class MergeCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MergeCommand
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
        $application->add(new MergeCommand());

        $this->command = $application->find('merge');
        $this->commandTester = new CommandTester($this->command);
    }

    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $targetFile = $this->testDir . '/merge.en.yml';
        @unlink($targetFile);

        $this->assertFalse(file_exists($targetFile));

        $this->commandTester->execute([
            'command'  => $this->command->getName(),
            'files'    => [
                $this->testDir . '/messages.en.yml',
                $this->testDir . '/validations.en.xliff',
            ],
            '--target' => $targetFile,
        ]);

        $expectedDisplay = sprintf('Translations merged to file "%s".', $targetFile);
        $expectedContent = <<<EOF
greeting: 'Hello World!'
name: 'John Doe'
text.min_length: 'Text is not long enough'
text.max_length: 'Text is too long'
text.email: 'Text is not a valid email'

EOF;

        $this->assertEquals($expectedDisplay, trim($this->commandTester->getDisplay()));
        $this->assertTrue(file_exists($targetFile));
        $this->assertEquals($expectedContent, file_get_contents($targetFile));
    }
}
