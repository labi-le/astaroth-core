<?php
declare(strict_types=1);

namespace App\Command;

use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Event\MessageNew;
use Astaroth\Attributes\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\BuilderFacade;
use Astaroth\TextMatcher;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class HelloWorld
{
    #[Message("привет", TextMatcher::START_AS)]
    public function hello(Data $data): void
    {
        BuilderFacade::create(
            (new \Astaroth\VkUtils\Builders\Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("приветик")
        );
    }
}