<?php

declare(strict_types=1);


namespace Astaroth\Route;


use Astaroth\Contracts\HandlerInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Astaroth\Route\Attribute\Executor;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use Throwable;

/**
 * Class Route
 * @package Astaroth\Route
 */
class Route
{
    private static array $class_map;

    public function __construct(private HandlerInterface $handler)
    {
    }


    /**
     * Set class map
     * @param string $class_map
     * @return static
     * @throws Exception
     */
    public function setClassMap(string $class_map): static
    {
        self::$class_map = ClassFinder::getClassesInNamespace($class_map, ClassFinder::RECURSIVE_MODE);
        return $this;
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
     * @param array $classMap
     * @param DataFetcher $data
     * @return void
     * @throws \ReflectionException
     *
     * @psalm-suppress PossiblyNullArgument
     */
    private static function attributeRouter(array $classMap, DataFetcher $data): void
    {
        $attrHandler = new EventAttributeHandler($classMap, $data);

        foreach ($attrHandler->validate() as $validatedObject) {
            (new Executor($validatedObject->getObject(), $validatedObject->getMethods()))
                ->replaceObjects(EventAttributeHandler::fetchData($data))
                ->launch(static function ($methodResult) {
                    new ReturnResultHandler($methodResult);
                });
        }
    }
}