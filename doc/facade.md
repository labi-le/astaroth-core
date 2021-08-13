# Astaroth

1. [Генератор клавиатуры](https://github.com/Sally-Framework/vk-keyboard)
2. Нативные фасады
    + [Отправить сообщение](#send-message)
    + [Создать пост](#create-post)
    + [Сделать запрос](#create-request)

### Send Message

Фасад облегчающий отправку сообщений
Можно использовать плейсхолдеры для динамики сообщений

````
Имя - %name
Имя с упоминанием "%@name"
Имя Фамилия "%full-name"
Имя Фамилия с упоминанием "%@full-name"
Фамилия "%last-name"
Фамилия с упоминанием "%@last-name"
````


```php
use Astaroth\Support\Facades\Message\BuilderFacade;

BuilderFacade::create(
    (new \Astaroth\VkUtils\Builders\Message())
        ->setPeerId(418618)
        ->setMessage("приветик %@name")
);

```

### Create post

Фасад облегчающий создание постов
```php
use Astaroth\Support\Facades\Message\BuilderFacade;

$photo = "https://sun9-56.userapi.com/impg/eWT80yOmtzyBYsYoWBRfK3uqcwqEQuYKRkEaBg/u2O02Ym1c6E.jpg?size=906x906&quality=96&sign=1dee09e1c58645b114dcb329817cf377&type=album";

BuilderFacade::create(
    (new \Astaroth\VkUtils\Builders\Post())
        ->setPeerId(418618)
        ->setMessage("Привет папищек!")
        ->setAttachments(...UploaderFacade::upload(new Photo($photo)))
);
```

### Create request

Фасад облегчающий запросы к vk api
```php
use Astaroth\Support\Facades\RequestFacade;

RequestFacade::request("users.get", ["user_ids" => 418618, "fields" => "sex"], "token");
```