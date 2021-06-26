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
use Astaroth\Attributes\Event\MessageNew;

#[MessageNew]
class HelloWorld
{
    //...
}
```

### MessageEvent

Указывается вначале класса, является событием

```php
use Astaroth\Attributes\Event\MessageEvent;

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
use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Event\MessageNew;

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
use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Message;
use Astaroth\Attributes\Event\MessageNew;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\TextMatcher as Validation;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Bar
{
    #[Message("содержится ли подстрока в другой подстроке", Validation::CONTAINS)]
    #[Message("заканчивается на", Validation::END_AS)]
    #[Message("начинается с", Validation::START_AS)]
    #[Message("похоже на", Validation::SIMILAR_TO)]
    #[Message("без валидации, сравнивает точь в точь")]
    public function method(Data $data)
}
```

### Attachment

Указывается для метода\
Необходим для определения типа вложения\
Можно указать количество вложений для определённого аттрибута

```php
use Astaroth\Attributes\Conversation;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Attributes\Attachment;
use Astaroth\Attributes\Event\MessageNew;

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

```php
use Astaroth\Attributes\Conversation;
use Astaroth\Attributes\Payload;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Attributes\Event\MessageNew;

#[Conversation(Conversation::ALL)]
#[MessageNew]
class Foo
{
    #[Payload(["button" => 12])]
    #[Payload(["button" => ["user_id" => 418618]])]
    public function method(Data $data)
}
```

