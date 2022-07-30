<?php

declare(strict_types=1);

namespace Astaroth\Bootstrap;

use Astaroth\Callback\Callback;
use Astaroth\Contracts\ConfigurationInterface;
use Astaroth\Contracts\HandlerInterface;
use Astaroth\Enums\Configuration\Type;
use Astaroth\Longpoll\Longpoll;
use Exception;

final class BotInstance
{
    public function __construct(private readonly ConfigurationInterface $container)
    {
    }

    /**
     * @implements HandlerInterface
     * @throws Exception
     */
    public function bootstrap(): HandlerInterface
    {
        return $this->selectStartupType($this->container);
    }

    /**
     * @throws Exception
     * @psalm-suppress TypeDoesNotContainType
     */
    private function selectStartupType(ConfigurationInterface $configuration): HandlerInterface
    {
        return $configuration->getType() === Type::CALLBACK
            ? $this->callback($configuration)
            : $this->longpoll($configuration);
    }

    /**
     * @throws Exception
     */
    private function callback(ConfigurationInterface $configuration): HandlerInterface
    {
        $callback = new Callback(
            $configuration->getCallbackConfirmationKey(),
            $configuration->getCallbackSecretKey(),
            $configuration->isHandleRepeatedRequest()
        );

        return $configuration->isDebug() ? $callback->disableClearHeaders() : $callback;
    }

    private function longpoll(ConfigurationInterface $configuration): HandlerInterface
    {
        return (new LongPoll(
            $configuration->getAccessToken(),
            $configuration->getApiVersion()
        ));
    }
}
