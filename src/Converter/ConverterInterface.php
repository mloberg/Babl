<?php
/**
 * ConverterInterface.php
 *
 * @package Mlo\Babl
 */

namespace Mlo\Babl\Converter;

/**
 * ConverterInterface
 *
 * @author  Matthew Loberg <loberg.matt@gmail.com>
 */
interface ConverterInterface
{
    /**
     * Add translation to output
     *
     * @param string $key
     * @param string $value
     */
    public function add($key, $value);

    /**
     * Get output
     *
     * @return string
     */
    public function getContent();

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension();
}
