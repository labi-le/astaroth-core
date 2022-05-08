<?php

declare(strict_types=1);

require_once "vendor/autoload.php";

use Astaroth\Auth\Configuration;
use Astaroth\Console\Logo;
use Astaroth\Console\Make\Command;
use Astaroth\Console\Make\Env;
use Astaroth\Enums\Configuration\ApplicationWorkMode;
use Astaroth\Foundation\Application;

$app = new Ahc\Cli\Application('    Lilit cli helper', Application::MINOR_VERSION);
$configuration = new Configuration(getcwd(), ApplicationWorkMode::DEVELOPMENT);

$app->add(new Command($configuration));
$app->add(new Env($configuration));

$app->logo(Logo::LILIT->value);
$app->handle($argv);