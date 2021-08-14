<?php
declare(strict_types=1);

namespace App\Command;

use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\Message;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\BuilderFacade;
use Astaroth\Support\Facades\UploaderFacade;
use Astaroth\VkUtils\Builders\Attachments\Photo;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Attachments
{
    /**
     * @throws \Exception
     */
    #[Message("котика")]
    public function cat(Data $data): void
    {
        $cat = static fn() => json_decode(file_get_contents("https://aws.random.cat/meow"), true)["file"];

        BuilderFacade::create(
            (new \Astaroth\VkUtils\Builders\Message())
                ->setPeerId($data->getPeerId())
                ->setAttachment(
                    ...UploaderFacade::upload(
                        new Photo($cat()), new Photo($cat())
                    )
                )
        );
    }
}