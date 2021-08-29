<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\Attachment;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class AttachmentTest extends TestCase
{
    private array $json_obj;

    protected function setUp(): void
    {
        $json = '[{"type":"photo","photo":{"album_id":-3,"date":1630235565,"id":457281385,"owner_id":259166248,"has_tags":false,"access_key":"8d73b853538b295088","sizes":[{"height":31,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=75x31&quality=96&sign=016839fae307ef3b6a4151efbcb2fff2&c_uniq_tag=FDuteZwZ2HqtLtrai2JfVkXEzBL2VQf8ZC6LoqP_zNw&type=album","type":"s","width":75},{"height":53,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x53&quality=96&sign=1210c30b3fc4c0fd3afcbbdba81d6656&c_uniq_tag=6JJGWt3d1qXw_n9cmK_VJiuNC-a2ln1jFvEzuA_JRyI&type=album","type":"m","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"x","width":266},{"height":87,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x87&quality=96&crop=51,0,163,109&sign=9dc98ea70e34ec03fc9a7ec066b572fb&c_uniq_tag=iCUPKn90qQ620QgIReqmxTA2DQY1AznbEjE8Dy2Lab8&type=album","type":"o","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=200x109&quality=96&crop=33,0,200,109&sign=d6550774cbd6af397ac1a56449a16de8&c_uniq_tag=oLniH0Ny_bVBN5JZkuJ2tgwEDh9SoOq4temnoeRTGs0&type=album","type":"p","width":200},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"q","width":266},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"r","width":266}],"text":""}},{"type":"photo","photo":{"album_id":-3,"date":1630235565,"id":457281386,"owner_id":259166248,"has_tags":false,"access_key":"1f72efe606ae12fd92","sizes":[{"height":31,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=75x31&quality=96&sign=016839fae307ef3b6a4151efbcb2fff2&c_uniq_tag=FDuteZwZ2HqtLtrai2JfVkXEzBL2VQf8ZC6LoqP_zNw&type=album","type":"s","width":75},{"height":53,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x53&quality=96&sign=1210c30b3fc4c0fd3afcbbdba81d6656&c_uniq_tag=6JJGWt3d1qXw_n9cmK_VJiuNC-a2ln1jFvEzuA_JRyI&type=album","type":"m","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"x","width":266},{"height":87,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=130x87&quality=96&crop=51,0,163,109&sign=9dc98ea70e34ec03fc9a7ec066b572fb&c_uniq_tag=iCUPKn90qQ620QgIReqmxTA2DQY1AznbEjE8Dy2Lab8&type=album","type":"o","width":130},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=200x109&quality=96&crop=33,0,200,109&sign=d6550774cbd6af397ac1a56449a16de8&c_uniq_tag=oLniH0Ny_bVBN5JZkuJ2tgwEDh9SoOq4temnoeRTGs0&type=album","type":"p","width":200},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"q","width":266},{"height":109,"url":"https://sun9-80.userapi.com/impg/d13Zho8kkEgBVCtr5h3aTz3H-ujhyEzWhFjjWQ/cHa2yTAlsB0.jpg?size=266x109&quality=96&sign=f004e86138958b56f4eb70b7b4548dc1&c_uniq_tag=I6RYu_GkGOBC-FYgXSGeMvrcg8PJiPMpHY7Vhge7tLA&type=album","type":"r","width":266}],"text":""}}]';
        $this->json_obj = json_decode($json, false);
    }


    public function testSetHaystack()
    {
        $hs = (new Attachment(Attachment::PHOTO, 2))
            ->setHaystack($this->json_obj);

        assertEquals(Attachment::class, $hs::class);
    }

    public function testValidate()
    {
        $hs = (new Attachment(Attachment::PHOTO, 2))
            ->setHaystack($this->json_obj);

        assertTrue($hs->validate());
    }
}
