<?php
/*
 * Copyright (c) 2016 Matthew Loberg
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
use Mlo\Babl\Utility\FileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MergeCommand
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class MergeCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('merge')
            ->setDescription('Merge multiple translation files together')
            ->addArgument('files', InputArgument::IS_ARRAY, 'Files to merge together')
            ->addOption(
                'target',
                't',
                InputOption::VALUE_OPTIONAL,
                'Target file (if none, will use first file)'
            )
            ->addOption(
                'force',
                null,
                InputOption::VALUE_NONE,
                'Will overwrite target file without asking'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $processorResolver = new ProcessorResolver([
            new YamlProcessor(),
            new XliffProcessor(),
            new PhpProcessor(),
        ]);

        $data = [];

        foreach ($input->getArgument('files') as $file) {
            $fileInfo = new FileInfo($file);

            $processor = $processorResolver->resolve($fileInfo->getExtension());

            if (false === $processor) {
                throw new \RuntimeException(sprintf('Unknown extension "%s".', $fileInfo->getExtension()));
            }

            $data = array_merge($data, $processor->process($file));
        }

        $target = $input->getOption('target') ?: $input->getArgument('files')[0];
        $fileInfo = new FileInfo($target);

        $converterResolver = new ConverterResolver([
            new XliffConverter(),
            new YamlConverter(),
            new PhpConverter(),
        ]);

        $converter = $converterResolver->resolve($fileInfo->getExtension());

        if (false === $converter) {
            throw new \RuntimeException(sprintf('Unknown extension "%s".', $fileInfo->getExtension()));
        }

        file_put_contents($target, $converter->convert($data));

        $output->writeln(sprintf('<info>Translations merged to file "%s".', $target));
    }
}
