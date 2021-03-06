<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * AddCommand
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class AddCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('add')
            ->setDescription('Add translation entry to file')
            ->addArgument('file', InputArgument::REQUIRED, 'File to add translation entry to')
            ->addArgument('key', InputArgument::REQUIRED, 'Translation key')
            ->addArgument('value', InputArgument::OPTIONAL, 'Translation value (if none given, it will prompt)')
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
        $key    = $input->getArgument('key');
        $value  = $input->getArgument('value');
        $helper = $this->getHelper('question');

        if (empty($value)) {
            $question = new Question(sprintf('Translation value for "%s": ', $key), '');
            $value = $helper->ask($input, $output, $question);
        }

        $fileInfo = $this->getFileInfo($file);

        $processor = $this->getProcessor($fileInfo->getExtension());
        $converter = $this->getConverter($fileInfo->getExtension());

        $data = $processor->process($file);

        $text     = sprintf('Translation key "%s" already exists. Update value? [yn] ', $key);
        $question = new ConfirmationQuestion($text, false);

        if (
            false === $input->getOption('force') &&
            array_key_exists($key, $data) &&
            !$helper->ask($input, $output, $question)
        ) {
            return;
        }

        $data[$key] = $value;

        file_put_contents($file, $converter->convert($data));

        $output->writeln(sprintf('<info>Translation key "%s" added to "%s".', $key, $file));
    }
}
