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
            "payload": {"foo":"bar"},
            "text": "uwuwu",
            "attachments": [{"type":"photo","photo":{"album_id":-3,"date":1630235565,"id":457281385,"owner_id":259166248,"has_tags":false,"access_key":"8d73b853538b295088","sizes":[{"height":31,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=75x31&quality=96&sign=016839fae307ef3b6a4151efbcb2fff2&c_uniq_tag=FDuteZwZ2HqtLtrai2JfVkXEzBL2VQf8ZC6LoqP_zNw&type=album","type":"s","width":75},{"height":53,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x53&quality=96&sign=1210c30b3fc4c0fd3afcbbdba81d6656&c_uniq_tag=6JJGWt3d1qXw_n9cmK_VJiuNC-a2ln1jFvEzuA_JRyI&type=album","type":"m","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"x","width":266},{"height":87,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x87&quality=96&crop=51,0,163,109&sign=9dc98ea70e34ec03fc9a7ec066b572fb&c_uniq_tag=iCUPKn90qQ620QgIReqmxTA2DQY1AznbEjE8Dy2Lab8&type=album","type":"o","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=200x109&quality=96&crop=33,0,200,109&sign=d6550774cbd6af397ac1a56449a16de8&c_uniq_tag=oLniH0Ny_bVBN5JZkuJ2tgwEDh9SoOq4temnoeRTGs0&type=album","type":"p","width":200},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"q","width":266},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"r","width":266}],"text":""}},{"type":"photo","photo":{"album_id":-3,"date":1630235565,"id":457281386,"owner_id":259166248,"has_tags":false,"access_key":"1f72efe606ae12fd92","sizes":[{"height":31,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=75x31&quality=96&sign=016839fae307ef3b6a4151efbcb2fff2&c_uniq_tag=FDuteZwZ2HqtLtrai2JfVkXEzBL2VQf8ZC6LoqP_zNw&type=album","type":"s","width":75},{"height":53,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x53&quality=96&sign=1210c30b3fc4c0fd3afcbbdba81d6656&c_uniq_tag=6JJGWt3d1qXw_n9cmK_VJiuNC-a2ln1jFvEzuA_JRyI&type=album","type":"m","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"x","width":266},{"height":87,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x87&quality=96&crop=51,0,163,109&sign=9dc98ea70e34ec03fc9a7ec066b572fb&c_uniq_tag=iCUPKn90qQ620QgIReqmxTA2DQY1AznbEjE8Dy2Lab8&type=album","type":"o","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=200x109&quality=96&crop=33,0,200,109&sign=d6550774cbd6af397ac1a56449a16de8&c_uniq_tag=oLniH0Ny_bVBN5JZkuJ2tgwEDh9SoOq4temnoeRTGs0&type=album","type":"p","width":200},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"q","width":266},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"r","width":266}],"text":""}}],
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
            "inline_keyboard": false,
            "carousel": true,
            "lang_id": 0
        }
    },
    "group_id": 201058446,
    "event_id": "2ca14315a711218d7392a46b44e22e74ad6273c3"
}';

return (new DataFetcher(json_decode($data, false, 512, JSON_THROW_ON_ERROR)));
