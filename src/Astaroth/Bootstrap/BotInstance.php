<?php

declare(strict_types=1);

namespace Astaroth\Bootstrap;


use Astaroth\Auth\Configuration;
use Astaroth\Callback\Callback;
use Astaroth\Contracts\HandlerInterface;
use Astaroth\Longpoll\Longpoll;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BotInstance
{
    public function __construct(private ContainerBuilder $container)
    {
    }

    /**
     * @implements HandlerInterface
     * @throws \Exception
     */
    public function bootstrap(): HandlerInterface
    {
        return $this->selectStartupType($this->container);
    }

    /**
     * @throws \Exception
     */
    private function selectStartupType(ContainerBuilder $container): HandlerInterface
    {
        return $container->getParameter(Configuration::TYPE) === Configuration::CALLBACK
            ? $this->callback($container)
            : $this->longpoll($container);
    }

    /**
     * @throws \Exception
     */
    private function callback(ContainerBuilder $container): HandlerInterface
    {
        [$secret_key, $handle_repeated_requests, $confirmation, $is_debug] =
            [
                $container->getParameter(Configuration::SECRET_KEY),
                $container->getParameter(Configuration::HANDLE_REPEATED_REQUESTS) === Configuration::YES,
                $container->getParameter(Configuration::CONFIRMATION_KEY),
                $container->getParameter(Configuration::DEBUG) === Configuration::YES,
            ];

        $callback = new Callback(
            $confirmation,
            $secret_key ?: null,
            $handle_repeated_requests
        );

        return $is_debug ? $callback->disableClearHeaders() : $callback;
    }

    private function longpoll(ContainerBuilder $container): HandlerInterface
    {
        return (new LongPoll(
            $container->getParameter(Configuration::ACCESS_TOKEN),
            $container->getParameter(Configuration::API_VERSION)
        ));
    }
}