<?php

declare(strict_types=1);

namespace Astaroth\Route;

use Astaroth\Contracts\HandlerInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Foundation\Executor;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use ReflectionException;
use RuntimeException;
use Throwable;

/**
 * Class Route
 * @package Astaroth\Route
 */
final class Route
{
    /** @var string[] */
    private static array $class_map;

    /**
     * @param HandlerInterface $handler
     * @param string $commandNamespace
     * @throws Exception
     */
    public function __construct(private readonly HandlerInterface $handler, string $commandNamespace)
    {
        self::$class_map = ClassFinder::getClassesInNamespace($commandNamespace, ClassFinder::RECURSIVE_MODE);
        if (self::$class_map === []) {
            throw new RuntimeException('No classes found in namespace: ' . $commandNamespace);
        }
    }


    /**
     * Routing data from VK
     * @throws Throwable
     *
     * @psalm-suppress PossiblyNullArgument
     */
    public function handle(): void
    {
        $this->handler->listen(static function (DataFetcher $data) {
            self::attributeRouter(self::$class_map, $data);

            /**
             * маловероятно что я это сделаю, но как идею нужно записать
             * TODO: помимо аттрибутов добавить обработку маршрутов по конфигурационному файлу, примерно как в Laravel (api.php)
             *
             * TODO: реализовать конфиг роутов
             *
             * draft
             * message === "hi" -> Message::HelloCommand()
             * payload["key" => "val", Payload::STRICT] -> Keyboard::ButtonPress
             * etc...
             */
        });
    }

    /**
     * @param string[] $classMap
     * @param DataFetcher $data
     * @return void
     * @throws ReflectionException
     *
     * @psalm-suppress PossiblyNullArgument
     */
    private static function attributeRouter(array $classMap, DataFetcher $data): void
    {
        $attrHandler = new EventAttributeHandler($classMap, $data);

        // TODO ускорить поиск класса по аттрибутам
        foreach ($attrHandler->validate() as $validatedObject) {
            (new Executor($validatedObject->getObject(), $validatedObject->getMethods(), [EventAttributeHandler::fetchData($data)]))
                ->launch(static function (mixed $methodResult) {
                    new ReturnResultHandler($methodResult);
                });
        }
    }
}
