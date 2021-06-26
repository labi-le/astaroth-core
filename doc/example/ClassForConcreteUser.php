<?php

namespace App\Command;

use Astaroth\Attributes\Attachment;
use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Event\MessageNew;
use Astaroth\Attributes\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Message\MessageConstructor;
use Astaroth\Support\Facades\Message\MessageUploaderFacade;
use Astaroth\VkUtils\Builders\MessageBuilder;
use Astaroth\VkUtils\Uploading\Objects\Photo;

/**
 * Class ClassForConcreteUser
 * Этот класс будет доступен только для id418618
 * @package App\Command
 */
#[Conversation(Conversation::PERSONAL_DIALOG, 418618)]
#[MessageNew]
class ClassForConcreteUser
{
    #[Message("привет")]
    public function method(Data $data)
    {
        MessageConstructor::create(function (MessageBuilder $builder) use ($data) {
            return $builder
                ->setPeerId($data->getPeerId())
                ->setMessage("Ого! Привет давно не виделись!");
        });
    }

    #[Attachment(Attachment::PHOTO)]
    public function photoAction(Data $data)
    {
        MessageConstructor::create(function (MessageBuilder $builder) use ($data) {
            return $builder
                ->setPeerId($data->getPeerId())
                ->setMessage("Красотища!");
        });

    }

    #[Message("картинку")]
    public function uploadAttachment(Data $data)
    {
        $photo = "https://sun9-56.userapi.com/impg/eWT80yOmtzyBYsYoWBRfK3uqcwqEQuYKRkEaBg/u2O02Ym1c6E.jpg?size=906x906&quality=96&sign=1dee09e1c58645b114dcb329817cf377&type=album";
        MessageConstructor::create(function (MessageBuilder $builder) use ($photo, $data) {
            return $builder
                ->setPeerId($data->getPeerId())
                ->setMessage("мяу")
                ->setAttachment(MessageUploaderFacade::upload(new Photo($photo)));
        });
    }
}