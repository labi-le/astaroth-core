<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use SplQueue;
use function array_walk;

/**
 * Class Queue
 * Scene analog
 * @package Astaroth\Foundation
 * @deprecated
 */
class Queue
{
    public const CURRENT = "current_queue";
    public const COUNT = "count_queue";

    public SplQueue $queue;
    private Session $session;

    /**
     * Queue constructor.
     * We create a queue object and fill it with anonymous functions
     * @param Session $session
     * @param callable ...$queue
     * @example function(Queue $q){};
     */
    public function __construct(Session $session, callable ...$queue)
    {
        $this->queue = new SplQueue();
        array_walk($queue, fn($elem) => $this->queue->enqueue($elem));

        $this->session = $session;

        $this->createSession();
        $this->run();
    }

    /**
     * Create session if missing
     */
    private function createSession(): void
    {
        if ($this->getCurrentQueue() === null) {
            $this->session->put(self::COUNT, $this->queue->count());
            $this->session->put(self::CURRENT, 1);
        }
    }

    /**
     * Change the queue to a specific
     * @param int $queue
     * @return void
     */
    private function changeCurrentQueue(int $queue): void
    {
        $this->session->put(self::CURRENT, $queue);
    }

    /**
     * Get the current queue
     * @return int|null
     */
    private function getCurrentQueue(): ?int
    {
        return $this->session->get(self::CURRENT);
    }

    /**
     * Get queue length
     * @return int|null
     */
    public function getLengthQueue(): ?int
    {
        return $this->session->get(self::COUNT);
    }

    /**
     * Process the chain of events
     */
    private function run(): void
    {
        $this->queue->rewind();

        while ($this->getCurrentQueue() > $this->queue->key()) {
            $this->queue->next();
        }

        $current = $this->queue->current();
        if ($current !== null) {
            $current($this);
        }
    }

    /**
     * Move to the next item in the queue
     */
    public function next(): void
    {
        $currentQueue = $this->getCurrentQueue();
        $this->changeCurrentQueue(++$currentQueue);
    }

    /**
     * Move to the prev item in the queue
     */
    public function prev(): void
    {
        $currentQueue = $this->getCurrentQueue();
        $this->changeCurrentQueue(--$currentQueue);
        $this->queue->prev();
    }

    /**
     * Move to the start item in the queue
     */
    public function rewind(): void
    {
        $this->changeCurrentQueue(1);
        $this->queue->rewind();
    }

    /**
     * Delete queue
     */
    public function leave(): void
    {
        $this->session->purge();
    }

    /**
     * Write the required data to the queue object
     * @param string|array $key
     * @param string|array $value
     * @return bool
     */
    public function put(string|array $key, string|array $value): bool
    {
        return $this->session->put($key, $value);
    }

    /**
     * Get the required data in a queued object
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->session->get($key);
    }

}