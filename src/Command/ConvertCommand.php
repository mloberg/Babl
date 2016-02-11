<?php
/*
 * Copyright (c) 2015 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Command;

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
class ConvertCommand extends AbstractCommand
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

        $fileInfo = $this->getFileInfo($file);

        $processor = $this->getProcessor($fileInfo->getExtension());
        $converter = $this->getConverter($format);

        $data = $processor->process($file);

        $outFile = sprintf(
            '%s/%s.%s.%s',
            $fileInfo->getDirectory(),
            $fileInfo->getName(),
            $fileInfo->getLanguage(),
            $converter->getExtension()
        );

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
        file_put_contents($outFile, $converter->convert($data));

        $output->writeln(sprintf('<info>File converted to %s.</info>', $outFile));
    }
}
