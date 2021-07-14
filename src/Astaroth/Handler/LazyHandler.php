<?php


namespace Astaroth\Handler;


/**
 * Class LazyHandler
 * @package Astaroth\Handler
 */
class LazyHandler implements \Astaroth\Interface\HandlerInterface
{

    public function __construct(private \Astaroth\CallBack\CallBack|\Astaroth\LongPoll\LongPoll $botInstance)
    {

    }

    /**
     * Normalize output data from VKontakte
     * @param object|array $data
     * @return \Astaroth\DataFetcher\DataFetcher
     */
    private function normalizeData(object|array $data): \Astaroth\DataFetcher\DataFetcher
    {
        if (is_array($data)) {
            $toJson = json_encode($data);
            $toObject = json_decode($toJson, false);
            return new \Astaroth\DataFetcher\DataFetcher($toObject);
        }
        return new \Astaroth\DataFetcher\DataFetcher($data);

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