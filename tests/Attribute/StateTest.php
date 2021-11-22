<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\State;
use Astaroth\Auth\Configuration;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Session;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function dirname;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class StateTest extends TestCase
{
    private DataFetcher $data;
    private Session $session;

    protected function setUp(): void
    {
        $json = '{"type":"message_new","object":{"message":{"date":1630235567,"from_id":418618,"id":1709,"out":0,"peer_id":259166248,"text":"","conversation_message_id":1670,"fwd_messages":[],"important":false,"random_id":0,"attachments":[{"type":"photo","photo":{"album_id":-3,"date":1630235565,"id":457281385,"owner_id":259166248,"has_tags":false,"access_key":"8d73b853538b295088","sizes":[{"height":31,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=75x31&quality=96&sign=016839fae307ef3b6a4151efbcb2fff2&c_uniq_tag=FDuteZwZ2HqtLtrai2JfVkXEzBL2VQf8ZC6LoqP_zNw&type=album","type":"s","width":75},{"height":53,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x53&quality=96&sign=1210c30b3fc4c0fd3afcbbdba81d6656&c_uniq_tag=6JJGWt3d1qXw_n9cmK_VJiuNC-a2ln1jFvEzuA_JRyI&type=album","type":"m","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"x","width":266},{"height":87,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x87&quality=96&crop=51,0,163,109&sign=9dc98ea70e34ec03fc9a7ec066b572fb&c_uniq_tag=iCUPKn90qQ620QgIReqmxTA2DQY1AznbEjE8Dy2Lab8&type=album","type":"o","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=200x109&quality=96&crop=33,0,200,109&sign=d6550774cbd6af397ac1a56449a16de8&c_uniq_tag=oLniH0Ny_bVBN5JZkuJ2tgwEDh9SoOq4temnoeRTGs0&type=album","type":"p","width":200},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"q","width":266},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"r","width":266}],"text":""}},{"type":"photo","photo":{"album_id":-3,"date":1630235565,"id":457281386,"owner_id":259166248,"has_tags":false,"access_key":"1f72efe606ae12fd92","sizes":[{"height":31,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=75x31&quality=96&sign=016839fae307ef3b6a4151efbcb2fff2&c_uniq_tag=FDuteZwZ2HqtLtrai2JfVkXEzBL2VQf8ZC6LoqP_zNw&type=album","type":"s","width":75},{"height":53,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x53&quality=96&sign=1210c30b3fc4c0fd3afcbbdba81d6656&c_uniq_tag=6JJGWt3d1qXw_n9cmK_VJiuNC-a2ln1jFvEzuA_JRyI&type=album","type":"m","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"x","width":266},{"height":87,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x87&quality=96&crop=51,0,163,109&sign=9dc98ea70e34ec03fc9a7ec066b572fb&c_uniq_tag=iCUPKn90qQ620QgIReqmxTA2DQY1AznbEjE8Dy2Lab8&type=album","type":"o","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=200x109&quality=96&crop=33,0,200,109&sign=d6550774cbd6af397ac1a56449a16de8&c_uniq_tag=oLniH0Ny_bVBN5JZkuJ2tgwEDh9SoOq4temnoeRTGs0&type=album","type":"p","width":200},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"q","width":266},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"r","width":266}],"text":""}}],"is_hidden":false},"client_info":{"button_actions":["text","vkpay","open_app","location","open_link","callback","intent_subscribe","intent_unsubscribe"],"keyboard":true,"inline_keyboard":true,"carousel":true,"lang_id":0}},"group_id":200982061,"event_id":"c1b1934f9232fd3221bdfd020a3f1ca78ced263f"}';
        $this->data = new DataFetcher(json_decode($json, false));

        $this->session = new Session(418618, State::RESERVED_NAME, sys_get_temp_dir());
        $this->session->put("example", true);
    }

    protected function tearDown(): void
    {
        $this->session->purge(false);
    }

    public function testSetHaystack()
    {
        $hs = (new State("button_set", State::USER))->setHaystack($this->data);
        assertEquals(State::class, $hs::class);

        $hs = (new State("button_set", State::USER))->setHaystack($this->data->messageNew());
        assertEquals(State::class, $hs::class);

        $hs = (new State("button_set", State::USER))->setHaystack($this->data->messageEvent());
        assertEquals(State::class, $hs::class);


    }

    public function testValidate()
    {
        FacadePlaceholder::getInstance(new ContainerBuilder(), Configuration::set(dirname(__DIR__)));

        $hs = (new State("example", State::USER))->setHaystack($this->data);
        assertTrue($hs->validate());
    }
}
