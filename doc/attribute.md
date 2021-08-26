# Astaroth

___

## Содержание

1. [Что такое аттрибуты?](https://www.php.net/manual/ru/language.attributes.overview.php)
2. Список аттрибутов
   + [MessageNew](#MessageNew)
   + [MessageEvent](#MessageEvent)

   - [Conversation](#Conversation)
   - [Message](#Message)
   - [Attachment](#Attachment)
   - [Payload](#Payload)

### MessageNew

Указывается вначале класса, является событием

```php
use Astaroth\Attribute\Event\MessageNew;

#[MessageNew]
class HelloWorld
{
    //...
}
```

### MessageEvent

Указывается вначале класса, является событием

```php
use Astaroth\Attribute\Event\MessageEvent;

#[MessageEvent]
class Event
{
    //...
}
```

### Conversation

Указывается вначале класса\
Необходим для определения типа конференции\
Можно указать id объектов для которых будут доступны методы

```php
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;

#[Conversation(Conversation::PERSONAL_DIALOG, 418618, 1234)]
#[MessageNew]
class Foo
{
    //...
}
```

### Message

Указывается для метода\
Необходим для определения текста сообщения\
Можно указать тип валидации

```php
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\DataFetcher\Events\MessageNew as Data;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Bar
{
    #[Message("содержится ли подстрока в другой подстроке", Message::CONTAINS)]
    #[Message("заканчивается на", Message::END_AS)]
    #[Message("начинается с", Message::START_AS)]
    #[Message("похоже на", Message::SIMILAR_TO)]
    #[Message("без валидации, сравнивает точь в точь")]
    public function method(Data $data){//...}
}
```

### Attachment

Указывается для метода\
Необходим для определения типа вложения\
Можно указать количество вложений для определённого аттрибута

```php
use Astaroth\Attribute\Conversation;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\Event\MessageNew;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Foo
{
    #[Attachment(Attachment::AUDIO)]
    #[Attachment(Attachment::AUDIO_MESSAGE)]
    #[Attachment(Attachment::DOC)]
    #[Attachment(Attachment::GRAFFITI)]
    #[Attachment(Attachment::PHOTO, 2)]
    #[Attachment(Attachment::STICKER)]
    #[Attachment(Attachment::VIDEO)]
    public function method(Data $data)
}
```

### Payload

Указывается для метода\
Необходим для определения payload (нажатие на кнопку)\
Указывается массив

Можно указать тип сравнения

```php
Payload::STRICT //строгое сравнение "точь в точь"
Payload::KEY_EXISTS // проверка на содержания ключей
Payload::CONTAINS // проверка на схожесть массивов
```

```php
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Payload;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Attribute\Event\MessageNew;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Foo
{
    #[Payload(["button" => 12])]
    #[Payload(["button" => ["user_id" => 418618]])]
    #[Payload(["button" => ["user_id" => 418618]], Payload::KEY_EXISTS)]
    public function method(Data $data){//...}
}
```

