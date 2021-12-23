#!/usr/bin/env php
<?php

declare(strict_types=1);

require_once "vendor/autoload.php";

use Astaroth\Console\Make\Command;
use Astaroth\Foundation\Application;

new Application(__DIR__);

$app = new Ahc\Cli\Application('Astaroth-Cli', 'v0.0.1');

$app->add(new Command, "c");

$app->logo(' █████╗ ███████╗████████╗ █████╗ ██████╗  ██████╗ ████████╗██╗  ██╗
██╔══██╗██╔════╝╚══██╔══╝██╔══██╗██╔══██╗██╔═══██╗╚══██╔══╝██║  ██║
███████║███████╗   ██║   ███████║██████╔╝██║   ██║   ██║   ███████║
██╔══██║╚════██║   ██║   ██╔══██║██╔══██╗██║   ██║   ██║   ██╔══██║
██║  ██║███████║   ██║   ██║  ██║██║  ██║╚██████╔╝   ██║   ██║  ██║
╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝    ╚═╝   ╚═╝  ╚═╝
                                                                   ');
$app->handle($argv);