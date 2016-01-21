<?php
/*
 * Copyright (c) 2016 Matthew Loberg
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Mlo\Babl;

use Mlo\Babl\Command\ConvertCommand;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Application
 *
 * This overrides Symfony's Console Application to allow a single command
 * without any other options. (`babl convert` becomes `babl`)
 *
 * @author Matthew Loberg <loberg.matt@gmail.com>
 */
class Application extends \Symfony\Component\Console\Application
{
    /**
     * @inheritdoc
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'convert';
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new ConvertCommand();

        return $defaultCommands;
    }

    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}
