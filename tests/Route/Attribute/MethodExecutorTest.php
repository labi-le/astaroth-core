<?php
declare(strict_types=1);

namespace Route\Attribute;

require_once "testClass.php";

use Astaroth\Attribute\Debug;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\State;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Route\Attribute\Executor;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MethodExecutorTest extends TestCase
{
    private Executor $methodExecutor;
    private MessageNew $data;

    protected function setUp(): void
    {
        $data = '{
    "type": "message_new",
    "object": {
        "message": {
            "date": 1636985023,
            "from_id": 259166248,
            "id": 0,
            "out": 0,
            "peer_id": 2000000001,
            "text": "test",
            "attachments": [],
            "conversation_message_id": 30098,
            "fwd_messages": [],
            "important": false,
            "is_hidden": false,
            "random_id": 0
        },
        "client_info": {
            "button_actions": [
                "text",
                "vkpay",
                "open_app",
                "location",
                "open_link",
                "callback",
                "intent_subscribe",
                "intent_unsubscribe"
            ],
            "keyboard": true,
            "inline_keyboard": true,
            "carousel": true,
            "lang_id": 0
        }
    },
    "group_id": 201058446,
    "event_id": "2ca14315a711218d7392a46b44e22e74ad6273c3"
}';

        $this->data = (new DataFetcher(json_decode($data, false)))->messageNew();
        $this->methodExecutor = new Executor(new ReflectionClass(testClass::class));

        $this->methodExecutor
            ->setCallableValidateAttribute(function ($attribute) {

                if ($attribute instanceof AttributeValidatorInterface && ($attribute::class === Message::class || $attribute::class === MessageRegex::class)) {
                    $attribute->setHaystack($this->data->getText());
                }

                if ($attribute instanceof Debug || $attribute instanceof State) {
                    $attribute->setHaystack($this->data);
                }

                return $attribute->validate();

            })
            ->replaceObjects($this->data->messageNew());
    }

    public function testLaunch()
    {
        $this->methodExecutor->launch();
    }
}