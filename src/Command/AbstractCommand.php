<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Command;

use Mlo\Babl\Converter;
use Mlo\Babl\Processor;
use Mlo\Babl\Utility\FileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * AbstractCommand
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class AbstractCommand extends Command
{
    /**
     * @var Processor\ProcessorResolver
     */
    private $processorResolver;

    /**
     * @var Converter\ConverterResolver
     */
    private $converterResolver;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->processorResolver = new Processor\ProcessorResolver([
            new Processor\PhpProcessor(),
            new Processor\XliffProcessor(),
            new Processor\YamlProcessor(),
        ]);

        $this->converterResolver = new Converter\ConverterResolver([
            new Converter\PhpConverter(),
            new Converter\XliffConverter(),
            new Converter\YamlConverter(),
        ]);
    }

    /**
     * Get file info
     *
     * @param string $file
     *
     * @return FileInfo
     */
    protected function getFileInfo($file)
    {
        return new FileInfo($file);
    }

    /**
     * Get processor
     *
     * @param string $format
     *
     * @return Processor\ProcessorInterface
     * @throws \InvalidArgumentException If no processor was found for format
     */
    protected function getProcessor($format)
    {
        $processor = $this->processorResolver->resolve($format);

        if (false === $processor) {
            throw new \InvalidArgumentException(sprintf('Unknown format "%s".', $format));
        }

        return $processor;
    }

    /**
     * Get processor for file
     *
     * @param string $file
     *
     * @return Processor\ProcessorInterface
     */
    protected function getProcessorForFile($file)
    {
        $fileInfo = $this->getFileInfo($file);

        return $this->getProcessor($fileInfo->getExtension());
    }

    /**
     * Get converter
     *
     * @param string $format
     *
     * @return Converter\ConverterInterface
     * @throws \InvalidArgumentException If no converter was found for format
     */
    protected function getConverter($format)
    {
        $converter = $this->converterResolver->resolve($format);

        if (false === $converter) {
            throw new \InvalidArgumentException(sprintf('Unknown format "%s".', $format));
        }

        return $converter;
    }
}
