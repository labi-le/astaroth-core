<?php

declare(strict_types=1);

namespace Astaroth\Bootstrap;


use Astaroth\Auth\Configuration;
use Astaroth\Auth\ParameterMissingException;
use Astaroth\Callback\Callback;
use Astaroth\Contracts\HandlerInterface;
use Astaroth\Longpoll\Longpoll;

class BotInstance
{
    public function __construct(private Configuration $container)
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
    private function selectStartupType(Configuration $configuration): HandlerInterface
    {
        return $configuration->getType() === Configuration::CALLBACK
            ? $this->callback($configuration)
            : $this->longpoll($configuration);
    }

    /**
     * @throws \Exception
     */
    private function callback(Configuration $configuration): HandlerInterface
    {
        $callback = new Callback(
            $configuration->getCallbackConfirmationKey(),
            $configuration->getCallbackSecretKey(),
            $configuration->isHandleRepeatedRequest()
        );

        return $configuration->isDebug() ? $callback->disableClearHeaders() : $callback;
    }

    /**
     * @throws ParameterMissingException
     */
    private function longpoll(Configuration $configuration): HandlerInterface
    {
        return (new LongPoll(
            $configuration->getAccessToken(),
            $configuration->getApiVersion()
        ));
    }
}