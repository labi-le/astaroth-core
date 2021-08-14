<?php
declare(strict_types=1);

namespace App\Command;

use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\BuilderFacade;
use Astaroth\Support\Facades\UploaderFacade;
use Astaroth\VkUtils\Builders\Attachments\Photo;

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
    public function method(Data $data): void
    {
        BuilderFacade::create(
            (new \Astaroth\VkUtils\Builders\Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("Ого! Привет давно не виделись!")
        );
    }

    #[Attachment(Attachment::PHOTO)]
    public function photoAction(Data $data): void
    {
        BuilderFacade::create(
            (new \Astaroth\VkUtils\Builders\Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("Красотища!")
        );
    }

    /**
     * @throws \Exception
     */
    #[Message("картинку")]
    public function uploadAttachment(Data $data): void
    {
        $photo = "https://sun9-56.userapi.com/impg/eWT80yOmtzyBYsYoWBRfK3uqcwqEQuYKRkEaBg/u2O02Ym1c6E.jpg?size=906x906&quality=96&sign=1dee09e1c58645b114dcb329817cf377&type=album";
        BuilderFacade::create(
            (new \Astaroth\VkUtils\Builders\Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("мяу")
                ->setAttachment(...UploaderFacade::upload(new Photo($photo)))
        );
    }
}