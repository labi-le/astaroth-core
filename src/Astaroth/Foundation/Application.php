<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Auth\Configuration;
use Astaroth\Bootstrap\BotInstance;
use Astaroth\CallBack\CallBack;
use Astaroth\Handler\LazyHandler;
use Astaroth\LongPoll\LongPoll;
use Astaroth\Route\Route;
use Astaroth\Support\Facades\Facade;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Application
{
    private const VERSION = 1;

    public function __construct(string $config_dir = __DIR__)
    {
        $container = new ContainerBuilder();
        $configuration = (new Configuration($config_dir))
            ->get();

        $this->setContainerParameters($container, $configuration);
        $this->fillFacades($container);

        (new Route(
            new LazyHandler($this->raiseBot($container))))
            ->setClassMap($container->getParameter("APP_NAMESPACE"))
            ->handle();

    }

    private function setContainerParameters(ContainerBuilder $container, array $params): void
    {
        array_walk($params, static fn($value, $key) => $container->setParameter($key, $value));

    }

    private function raiseBot(ContainerBuilder $container): CallBack|LongPoll
    {
        return (new BotInstance($container))->bootstrap();
    }

    private function fillFacades(ContainerBuilder $container): void
    {
        $facades =
            [
                \Astaroth\VkUtils\Message::class,
                \Astaroth\VkUtils\Post::class,
                \Astaroth\VkUtils\Uploading\WallUploader::class,
                \Astaroth\VkUtils\Uploading\MessagesUploader::class,
            ];

        new Facade(
            ...array_map(static function ($facade) use ($container) {
                return (new $facade($container->getParameter("API_VERSION")))
                    ->setDefaultToken($container->getParameter("ACCESS_TOKEN"));
            }, $facades)
        );
    }
}