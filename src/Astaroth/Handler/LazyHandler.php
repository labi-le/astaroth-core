<?php

declare(strict_types=1);


namespace Astaroth\Handler;


use Astaroth\Contracts\HandlerInterface;
use Astaroth\DataFetcher\DataFetcher;
use Psr\Log\LoggerInterface;
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

    public function __construct(private readonly HandlerInterface $botInstance, private readonly LoggerInterface $logger)
    {
    }

    /**
     * Normalize output data from VKontakte
     * @param object|array $data
     * @return DataFetcher
     * @noinspection JsonEncodingApiUsageInspection
     * @psalm-suppress MixedAssignment, MixedArgument
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
     * @noinspection JsonEncodingApiUsageInspection
     * @psalm-suppress MissingClosureReturnType, MixedArgument, MissingClosureParamType
     */
    public function listen(callable $func): void
    {
        $this->botInstance->listen(
            function ($raw_data) use ($func) {
                $this->logger->info("new event\n" . json_encode($raw_data));
                return $func($this->normalizeData($raw_data));
            }
        );
    }
}