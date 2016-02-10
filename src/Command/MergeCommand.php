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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * MergeCommand
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class MergeCommand extends AbstractCommand
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
        $data = [];

        foreach ($input->getArgument('files') as $file) {
            $processor = $this->getProcessorForFile($file);
            $data = array_merge($data, $processor->process($file));
        }

        $target = $input->getOption('target') ?: $input->getArgument('files')[0];
        $extension = $this->getFileInfo($target)->getExtension();

        $converter = $this->getConverter($extension);

        file_put_contents($target, $converter->convert($data));

        $output->writeln(sprintf('<info>Translations merged to file "%s".', $target));
    }
}
