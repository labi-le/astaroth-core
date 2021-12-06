<?php

declare(strict_types=1);

namespace Astaroth\Commands;


use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Foundation\Utils;
use Astaroth\Support\Facades\Message as MessageFacade;
use Exception;
use JetBrains\PhpStorm\Pure;
use Throwable;

/**
 * Class BaseCommands
 * @package Astaroth\Commands
 */
abstract class BaseCommands
{
    public function __construct(protected MessageNew|MessageEvent|null $data = null)
    {
    }


    /**
     * @throws Throwable
     */
    final protected function message(string $text = ""): MessageFacade
    {
        return (new MessageFacade($this->data?->getPeerId()))->text($text);
    }

    final protected function request(string $accessToken = null): ApiRequest
    {
        return new ApiRequest($accessToken);
    }

    /**
     * @throws Throwable
     */
    final protected function logToMessage(int $id, string $error_level, Exception|string $e): void
    {
        Utils::logToMessage($id, $error_level, $e);
    }
}