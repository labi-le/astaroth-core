<?php

declare(strict_types=1);

namespace Astaroth\Contracts;

use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\DataFetcher\Events\WallPostNew;

interface InvokableInterface
{
    /**
     * @param MessageNew|MessageEvent|WallPostNew $data
     * @return void
     */
    public function __invoke(MessageNew|MessageEvent|WallPostNew $data): void;
}