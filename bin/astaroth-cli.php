<?php

declare(strict_types=1);

require_once "vendor/autoload.php";

use Astaroth\Console\Make\Command;
use Astaroth\Console\Make\Env;

$app = new Ahc\Cli\Application('Astaroth-Cli', 'v0.0.1');

$app->add(new Command, "c");
$app->add(new Env());

$app->logo(' █████╗ ███████╗████████╗ █████╗ ██████╗  ██████╗ ████████╗██╗  ██╗
██╔══██╗██╔════╝╚══██╔══╝██╔══██╗██╔══██╗██╔═══██╗╚══██╔══╝██║  ██║
███████║███████╗   ██║   ███████║██████╔╝██║   ██║   ██║   ███████║
██╔══██║╚════██║   ██║   ██╔══██║██╔══██╗██║   ██║   ██║   ██╔══██║
██║  ██║███████║   ██║   ██║  ██║██║  ██║╚██████╔╝   ██║   ██║  ██║
╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝    ╚═╝   ╚═╝  ╚═╝
                                                                   ');
$app->handle($argv);