<?php
declare(strict_types=1);


namespace Astaroth\Handler;


use Astaroth\CallBack\CallBack;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Contracts\HandlerInterface;
use Astaroth\LongPoll\LongPoll;

/**
 * Class LazyHandler
 * @package Astaroth\Handler
 */
class LazyHandler implements HandlerInterface
{

    public function __construct(private CallBack|LongPoll $botInstance)
    {

    }

    /**
     * Normalize output data from VKontakte
     * @param object|array $data
     * @return DataFetcher
     */
    private function normalizeData(object|array $data): DataFetcher
    {
        if (is_array($data)) {
            $toJson = json_encode($data);
            $toObject = json_decode($toJson, false);
            return new DataFetcher($toObject);
        }
        return new DataFetcher($data);

    }

    /**
     * Run instance longpoll\callback
     * @param callable $func
     * @throws \Throwable
     */
    public function listen(callable $func): void
    {
        $this->botInstance->listen(
            fn($raw_data) => $func($this->normalizeData($raw_data)));
    }
}