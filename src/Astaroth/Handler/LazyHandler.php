<?php

declare(strict_types=1);


namespace Astaroth\Handler;


use Astaroth\Contracts\HandlerInterface;
use Astaroth\DataFetcher\DataFetcher;
use Throwable;
use function is_array;
use function json_decode;
use function json_encode;

/**
 * Class LazyHandler
 * @package Astaroth\Handler
 */
final class LazyHandler implements HandlerInterface
{

    public function __construct(private readonly HandlerInterface $botInstance){}

    /**
     * Normalize output data from VKontakte
     * @param object|array $data
     * @return DataFetcher
     * @noinspection JsonEncodingApiUsageInspection
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
     * Run instance
     * @implements HandlerInterface
     * @param callable $func
     * @throws Throwable
     */
    public function listen(callable $func): void
    {
        $this->botInstance->listen(
            fn($raw_data) => $func($this->normalizeData($raw_data))
        );
    }
}