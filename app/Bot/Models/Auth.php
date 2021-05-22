<?php

declare(strict_types=1);


namespace Bot\Models;


use Astaroth\LongPoll\LongPoll;
use Astaroth\VkUtils\Message;
use Astaroth\VkUtils\Uploader;

final class Auth
{
    public function __construct(private string $token, private $version)
    {
    }

    public function getClients(): array
    {
        return [
            'longpoll' => (new LongPoll(version: $this->version))->setDefaultToken($this->token),
            'message' => (new Message($this->version))->setDefaultToken($this->token),
            'uploader' => (new Uploader($this->version))->setDefaultToken($this->token),
        ];
    }
}