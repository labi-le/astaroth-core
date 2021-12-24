<?php

declare(strict_types=1);

require_once "vendor/autoload.php";

use Astaroth\Console\Logo;
use Astaroth\Console\Make\Command;
use Astaroth\Console\Make\Env;
use Astaroth\Foundation\Application;

$app = new Ahc\Cli\Application('    Lilit cli helper', Application::MINOR_VERSION);

$app->add(new Command);
$app->add(new Env());

$app->logo(Logo::LILIT->value);
$app->handle($argv);