<?php
declare(strict_types=1);

namespace App\Command;

use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\BuilderFacade;


#[Conversation(Conversation::ALL)]
#[MessageNew]
class HelloWorld
{
    #[Message("привет", Message::START_AS)]
    public function hello(Data $data): void
    {
        BuilderFacade::create(
            (new \Astaroth\VkUtils\Builders\Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("приветик")
        );
    }
}
