<?php

declare(strict_types=1);

namespace Astaroth\Bootstrap;


use Astaroth\Auth\Configuration;
use Astaroth\Auth\InvalidParameterException;
use Astaroth\CallBack\CallBack;
use Astaroth\LongPoll\LongPoll;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class BotInstance
{
    public function __construct(private ContainerBuilder $container)
    {
    }

    public function bootstrap(): CallBack|LongPoll
    {
        return $this->selectStartupType($this->container);
    }

    private function selectStartupType(ContainerBuilder $container): CallBack|LongPoll
    {
        return $container->getParameter(Configuration::TYPE) === Configuration::CALLBACK
            ? $this->callback($container)
            : $this->longpoll($container);
    }

    /**
     * @throws InvalidParameterException
     */
    private function callback(ContainerBuilder $container): CallBack
    {
        $SECRET_KEY = $container->getParameter(Configuration::SECRET_KEY);

        $HANDLE_REPEATED_REQUESTS = $container->getParameter(Configuration::HANDLE_REPEATED_REQUESTS);

        if ($HANDLE_REPEATED_REQUESTS === Configuration::YES) {
            $callbackHandlerRepeatedRequests = true;
        } elseif ($HANDLE_REPEATED_REQUESTS === Configuration::NO) {
            $callbackHandlerRepeatedRequests = false;
        } else {
            throw new InvalidParameterException("The processing of repeated requests for Callback is specified incorrectly");
        }

        return new CallBack(
            $container->getParameter(Configuration::CONFIRMATION_KEY),
            $SECRET_KEY !== "" ? $SECRET_KEY : null,
            $callbackHandlerRepeatedRequests,
        );
    }

    private function longpoll(ContainerBuilder $container): Longpoll
    {
        return (new LongPoll(version: $container->getParameter(Configuration::API_VERSION)
        ))->setDefaultToken($container->getParameter(Configuration::ACCESS_TOKEN));
    }
}