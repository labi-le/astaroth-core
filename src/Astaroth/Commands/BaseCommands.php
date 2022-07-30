<?php

declare(strict_types=1);

namespace Astaroth\Commands;

use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Foundation\Utils;
use Astaroth\Support\Facades\Message as MessageFacade;
use Exception;
use Throwable;

/**
 * Class BaseCommands
 * Commands which are used in user defined classes
 * @package Astaroth\Commands
 */
abstract class BaseCommands
{
    public function __construct(protected readonly MessageNew|MessageEvent|null $data = null)
    {
    }


    /**
     * Call message facade and send message
     * @throws Throwable
     */
    final protected function message(string $text = ""): MessageFacade
    {
        return (new MessageFacade($this->data?->getPeerId()))->text($text);
    }

    /**
     * Create a new request
     * @param string|null $accessToken
     * @return ApiRequest
     */
    final protected function request(string $accessToken = null): ApiRequest
    {
        return new ApiRequest($accessToken);
    }

    /**
     * Log data to message
     * @throws Throwable
     */
    final protected function logToMessage(int $id, string $error_level, Exception|string $e): void
    {
        Utils::logToMessage($id, $error_level, $e);
    }
}
