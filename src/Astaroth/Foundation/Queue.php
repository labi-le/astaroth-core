<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

/**
 * Class Queue
 * Scene analog
 * @package Astaroth\Foundation
 */
class Queue
{
    private const CURRENT = "current_queue";
    private const COUNT = "count_queue";

    public \SplQueue $queue;
    private Session $session;

    /**
     * Queue constructor.
     * We create a queue object and fill it with anonymous functions
     * @param int $id
     * @param string $queue_name
     * @param callable ...$queue
     * @example function(Queue $q){];
     */
    public function __construct(int $id, string $queue_name, callable ...$queue)
    {
        $this->queue = new \SplQueue();
        array_walk($queue, fn($elem) => $this->queue->enqueue($elem));

        $this->session = new Session($id, $queue_name);

        $this->createSession();
        $this->run();
    }

    /**
     * Create session if missing
     */
    private function createSession(): void
    {
        if ($this->getCurrentQueue() === null) {
            $this->session->put(static::COUNT, $this->queue->count());
            $this->session->put(static::CURRENT, 1);
        }
    }

    /**
     * Change the queue to a specific
     * @param int $queue
     * @return bool
     */
    private function changeCurrentQueue(int $queue): bool
    {
        return $this->session->put(static::CURRENT, $queue);
    }

    /**
     * Get the current queue
     * @return int|null
     */
    private function getCurrentQueue(): ?int
    {
        return $this->session->get(static::CURRENT);
    }

    /**
     * Get queue length
     * @return int|null
     */
    private function getLengthQueue(): ?int
    {
        return $this->session->get(static::COUNT);
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
        $this->session->purge(true);
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