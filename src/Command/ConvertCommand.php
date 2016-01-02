<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Command;

use Mlo\Babl\Converter\ConverterInterface;
use Mlo\Babl\Processor\PhpProcessor;
use Mlo\Babl\Processor\XliffProcessor;
use Mlo\Babl\Processor\YamlProcessor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * ConvertCommand
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class ConvertCommand extends Command
{
    /**
     * Format to converter map
     *
     * @var array
     */
    private $converterMap = [
        'xliff' => 'Mlo\Babl\Converter\XliffConverter',
        'xlif'  => 'Mlo\Babl\Converter\XliffConverter',
        'xlf'   => 'Mlo\Babl\Converter\XliffConverter',
        'yaml'  => 'Mlo\Babl\Converter\YamlConverter',
        'yml'   => 'Mlo\Babl\Converter\YamlConverter',
        'php'   => 'Mlo\Babl\Converter\PhpConverter',
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('convert')
            ->setDescription('Convert translation files')
            ->addArgument('file', InputArgument::REQUIRED, 'File to convert')
            ->addOption(
                'format',
                'f',
                InputArgument::OPTIONAL,
                'Format to save as',
                'xliff'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        $filename = basename($file);
        $dir = dirname($file);

        // Get data from filename: messages.en.yml
        preg_match('&^(.*?)\.(.*?)\.(.*)$&', $filename, $matches);
        list($fullName, $name, $targetLang, $extension) = $matches;

        // Target format
        $format = $input->getOption('format');

        // Make sure we support that format
        if (!array_key_exists($format, $this->converterMap)) {
            $output->writeln(sprintf("<error>Unsupported format %s</error>", $format));
            exit(1);
        }

        /** @var ConverterInterface $converter */
        $converter = new $this->converterMap[$format]($targetLang, $name);

        // Load data by extension
        if ($extension === 'yml' || $extension === 'yaml') {
            $data = new YamlProcessor($file);
        } elseif ($extension === 'xliff' || $extension === 'xlif' || $extension === 'xlf') {
            $data = new XliffProcessor($file);
        } elseif ($extension === 'php') {
            $data = new PhpProcessor($file);
        } else {
            $output->writeln(sprintf('<error>Unknown extension %s</error>', $extension));
            exit(1);
        }

        // Convert data
        foreach ($data as $key => $value) {
            $converter->add($key, $value);
        }

        // Save the file with a different extension
        $outFile = sprintf('%s/%s.%s.%s', $dir, $name, $targetLang, $converter->getExtension());

        // Confirm if the file already exists
        $helper = $this->getHelper('question');
        $text = sprintf("Target file %s exists. Overwrite this file? [yn] ", $outFile);
        $question = new ConfirmationQuestion($text, false);

        if (file_exists($outFile) && !$helper->ask($input, $output, $question)) {
            return;
        }

        // Save the file
        file_put_contents($outFile, $converter->getContent());

        $output->writeln(sprintf('<info>File converted to %s.</info>', $outFile));
    }
}
