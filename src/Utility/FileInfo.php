<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl\Utility;

/**
 * FileInfo
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class FileInfo
{
    /**
     * @var string
     */
    private $file;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $name;

    /**
     * Constructor
     *
     * @param string $file
     */
    public function __construct($file)
    {
        if (!preg_match('/.+\..+\..+$/', $file)) {
            throw new \InvalidArgumentException(sprintf(
                'File "%s" did not match the format [name].[lang].[ext].',
                $file
            ));
        }

        $this->file      = $file;
        $this->filename  = basename($file);
        $this->directory = dirname($file);

        $fileParts = explode('.', $this->filename);
        $this->extension = array_pop($fileParts);
        $this->language  = array_pop($fileParts);
        $this->name      = implode('.', $fileParts);
    }

    /**
     * Get File
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get Filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Get Directory
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get Extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get Language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
