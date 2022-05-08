<?php

namespace Astaroth\Console\Make;

use Ahc\Cli\Input\Command as CliCommand;
use Astaroth\Contracts\ConfigurableCommand;
use Astaroth\Contracts\ConfigurationInterface;
use function copy;
use function dirname;
use function getcwd;
use function sprintf;
use const DIRECTORY_SEPARATOR;

final class Env extends CliCommand implements ConfigurableCommand
{
    public function __construct(ConfigurationInterface $configuration)
    {
        parent::__construct("make:env", "generate env config");

        $this
            ->action(function () {
                copy(dirname(__DIR__, 4) . "/tests/.env", sprintf('%s%s.env', getcwd(), DIRECTORY_SEPARATOR));
            });
    }


}