<?php

namespace Mlo\Babl\Converter;

class ConverterResolver
{
    /**
     * @var ConverterInterface[]|array
     */
    private $converters;

    /**
     * Constructor
     *
     * @param array|ConverterInterface[] $converters
     */
    public function __construct(array $converters = [])
    {
        $this->converters = [];

        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }

    /**
     * Returns a converter that supports given extension
     *
     * @param string $format
     *
     * @return ConverterInterface|bool false if no supported converter found
     */
    public function resolve($format)
    {
        foreach ($this->converters as $converter) {
            if ($converter->supports($format)) {
                return $converter;
            }
        }

        return false;
    }

    /**
     * Add converter to resolver
     *
     * @param ConverterInterface $converter
     *
     * @return ConverterResolver
     */
    public function addConverter(ConverterInterface $converter)
    {
        $this->converters[] = $converter;

        return $this;
    }

    /**
     * Returns the registered converters
     *
     * @return array|ConverterInterface[]
     */
    public function getConverters()
    {
        return $this->converters;
    }
}
