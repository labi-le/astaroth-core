<?php

namespace Astaroth\Console\Make;

use Ahc\Cli\Input\Command as CliCommand;
use Astaroth\Foundation\Application;
use Astaroth\Foundation\Utils;
use Astaroth\Generators\EventGenerator;
use RuntimeException;

final class Command extends CliCommand
{
    public function __construct()
    {
        parent::__construct("make:command", "generate command");


        $this
            ->argument('className', 'name of the class that will be indicated when creating')
            ->argument('event', 'event which class should handle')
            ->usage(
                '<bold>  $0</end> <green>make:command</end> Greetings message_new</end><eol/>'
            )
            ->action(function (string $className, string $event) {
                new Application(getcwd());

                $path = Utils::replaceNamespaceToPath(Application::$configuration->getAppNamespace());
                if (@!mkdir($path, 0777, true) && !is_dir($path)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
                }

                file_put_contents(
                    sprintf('%s%s%s.php', $path, DIRECTORY_SEPARATOR, $className),
                    EventGenerator::generate(Application::$configuration->getAppNamespace(), $className, $event)
                );
            });
    }


}