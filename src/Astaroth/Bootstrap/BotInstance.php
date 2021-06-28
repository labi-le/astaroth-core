<?php

declare(strict_types=1);

namespace Astaroth\Bootstrap;


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
        return $container->getParameter("TYPE") === "CALLBACK"
            ? $this->callback($container)
            : $this->longpoll($container);
    }

    private function callback(ContainerBuilder $container): CallBack
    {
        $SECRET_KEY = $container->getParameter("SECRET_KEY");

        $_RAW_HANDLE_REPEATED_REQUESTS = $container->getParameter("HANDLE_REPEATED_REQUESTS");

        if (empty($_RAW_HANDLE_REPEATED_REQUESTS) || $_RAW_HANDLE_REPEATED_REQUESTS === "true") {
            $HANDLE_REPEATED_REQUESTS = true;
        } else {
            $HANDLE_REPEATED_REQUESTS = false;
        }

        return new CallBack(
            $container->getParameter("CONFIRMATION_KEY"),
            $SECRET_KEY !== "" ? $SECRET_KEY : null,
            $HANDLE_REPEATED_REQUESTS,
        );
    }

    private function longpoll(ContainerBuilder $container): Longpoll
    {
        $lp = new LongPoll(version: $container->getParameter("API_VERSION"));
        $lp->setDefaultToken($container->getParameter("ACCESS_TOKEN"));

        return $lp;
    }
}