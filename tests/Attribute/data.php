<?php

declare(strict_types=1);

use Astaroth\DataFetcher\DataFetcher;

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
            "action": {
                "type": "chat_invite_user",
                "member_id": -190405359
            },
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

return (new DataFetcher(json_decode($data, false)));
