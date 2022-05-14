<?php
declare(strict_types=1);

namespace Astaroth\Console\Make;

use Ahc\Cli\Input\Command as CliCommand;
use Astaroth\Enums\Events;
use Astaroth\Foundation\Application;
use Astaroth\Foundation\Utils;
use Astaroth\Generators\EventGenerator;
use Astaroth\Generators\NonExistentEventException;
use RuntimeException;
use function file_put_contents;
use function getcwd;
use function is_dir;
use function mkdir;
use function sprintf;
use const DIRECTORY_SEPARATOR;

final class Command extends CliCommand
{
    public function __construct()
    {
        parent::__construct("make:command", "generate command");

        $this
            ->argument('className', 'name of the class that will be indicated when creating', 'Command')
            ->argument('event', 'event which class should handle', Events::MESSAGE_NEW->value)
            ->usage(
                '<bold>  $0</end> <green>make:command</end> Greetings message_new</end><eol/>'
            )
            ->action(function ($className, $event) {
                if ($className === null || $event === null) {
                    throw new RuntimeException("You must specify a class name and an event");
                }

                $app = new Application(getcwd());
                $configuration = $app->getConfiguration();

                $path = Utils::replaceNamespaceToPath($configuration->getAppNamespace());
                if (@!mkdir($path, 0777, true) && !is_dir($path)) {
                    throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
                }

                try {
                    $file = EventGenerator::generate($configuration->getAppNamespace(), $className, $event);
                    $filePathName = sprintf('%s%s%s.php', $path, DIRECTORY_SEPARATOR, $className);

                    file_put_contents($filePathName, $file);

                    $this->writer()
                        ->ok("Action successfully", true)
                        ->info("Path from generated file:\n$filePathName", true);
                } catch (NonExistentEventException $e) {
                    $this->writer()->red($e->getMessage(), true);
                }

            });
    }


}