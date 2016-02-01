<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Command;

use Mlo\Babl\Converter\ConverterResolver;
use Mlo\Babl\Converter\PhpConverter;
use Mlo\Babl\Converter\XliffConverter;
use Mlo\Babl\Converter\YamlConverter;
use Mlo\Babl\Processor\PhpProcessor;
use Mlo\Babl\Processor\ProcessorResolver;
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
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('convert')
            ->setDescription('Convert translation files')
            ->addArgument('file', InputArgument::REQUIRED, 'File to convert')
            ->addArgument('format', InputArgument::OPTIONAL, 'Format to convert to (default: xliff)', 'xliff')
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Will overwrite key if it exists without asking.'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file   = $input->getArgument('file');
        $format = $input->getArgument('format');

        $filename = basename($file);
        $dir      = dirname($file);

        $fileParts = explode('.', $filename);
        $extension = array_pop($fileParts);
        $language  = array_pop($fileParts);
        $name      = implode('.', $fileParts);

        $processorResolver = new ProcessorResolver([
            new YamlProcessor(),
            new XliffProcessor(),
            new PhpProcessor(),
        ]);

        $processor = $processorResolver->resolve($extension);

        if (false === $processor) {
            throw new \RuntimeException(sprintf('Unknown extension "%s".', $extension));
        }

        $converterResolver = new ConverterResolver([
            new XliffConverter(),
            new YamlConverter(),
            new PhpConverter(),
        ]);

        $converter = $converterResolver->resolve($format);

        if (false === $converter) {
            throw new \RuntimeException(sprintf('Unknown format "%s".', $format));
        }

        $data = $processor->process($file);

        $outFile = sprintf('%s/%s.%s.%s', $dir, $name, $language, $converter->getExtension());

        $helper = $this->getHelper('question');
        $text = sprintf("Target file %s exists. Overwrite this file? [yn] ", $outFile);
        $question = new ConfirmationQuestion($text, false);

        if (
            false === $input->getOption('force') &&
            file_exists($outFile) &&
            !$helper->ask($input, $output, $question)
        ) {
            return;
        }

        // Save the file
        file_put_contents($outFile, $converter->convert($data, $filename, $name, $language));

        $output->writeln(sprintf('<info>File converted to %s.</info>', $outFile));
    }
}
