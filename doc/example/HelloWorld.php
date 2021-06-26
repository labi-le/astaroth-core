<?php

namespace App\Command;

use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Event\MessageNew;
use Astaroth\Attributes\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Message\MessageConstructor;
use Astaroth\TextMatcher;
use Astaroth\VkUtils\Builders\MessageBuilder;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class HelloWorld
{
    #[Message("привет", TextMatcher::START_AS)]
    public function hello(Data $data): void
    {
        MessageConstructor::create(function (MessageBuilder $builder) use ($data) {
            return $builder
                ->setPeerId($data->getPeerId())
                ->setMessage("приветик");
        });    }
}