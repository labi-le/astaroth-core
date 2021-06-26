<?php

namespace App\Command;

use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Event\MessageNew;
use Astaroth\Attributes\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Message\MessageConstructor;
use Astaroth\Support\Facades\Message\MessageUploaderFacade;
use Astaroth\VkUtils\Builders\MessageBuilder;
use Astaroth\VkUtils\Uploading\Objects\Photo;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Attachments
{
    #[Message("котика")]
    public function cat(Data $data): void
    {
        $cat = json_decode(file_get_contents("https://aws.random.cat/meow"), true);

        MessageConstructor::create(function (MessageBuilder $builder) use ($cat, $data) {
            return $builder
                ->setPeerId($data->getPeerId())
                ->setAttachment(MessageUploaderFacade::upload(new Photo($cat), new Photo("")));
        });
    }
}