<?php

namespace Astaroth\Console\Make;

use Ahc\Cli\Input\Command as CliCommand;
use function dirname;

final class Env extends CliCommand
{
    public function __construct()
    {
        parent::__construct("make:env", "generate env config");


        $this
            ->action(function () {
                copy(dirname(__DIR__, 4) . "/tests/.env", sprintf('%s%s.env', getcwd(), DIRECTORY_SEPARATOR));
            });
    }


}