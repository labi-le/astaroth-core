# Astaroth

1. [Генератор клавиатуры](https://github.com/Sally-Framework/vk-keyboard)
2. Нативные фасады
    + [Отправить сообщение](#send-message)
    + [Создать пост](#create-post)

### Send Message

Фасад облегчающий отправку сообщений
```php
use Astaroth\Support\Facades\Message\MessageConstructor;
use Astaroth\VkUtils\Contracts\IMessageBuilder;
use Astaroth\Support\Facades\Message\MessageUploaderFacade;
use Astaroth\VkUtils\Uploading\Objects\Photo;

MessageConstructor::create(static function(IMessageBuilder $builder){
    return $builder
        ->setPeerId(418618)
        ->setMessage("Привет")
        ->setAttachment(
            MessageUploaderFacade::upload(
                new Photo("...path"), new Photo("...path")
                ),
        );
});

```

### Create post

Фасад облегчающий создание постов
```php
use Astaroth\Support\Facades\Wall\PostConstructor;
use Astaroth\VkUtils\Contracts\IPostBuilder;
use Astaroth\Support\Facades\Wall\WallUploaderFacade;
use Astaroth\VkUtils\Uploading\Objects\Photo;

PostConstructor::create(static function(IPostBuilder $builder){
    return $builder
        ->setMessage("Привет папищек!")
        ->setAttachments(WallUploaderFacade::upload(new Photo("...path"), new Photo("...path")))
        
});
```

### Request api

Фасад облегчающий запросы к vk api
```php
use Astaroth\Support\Facades\RequestFacade;

RequestFacade::request("users.get", ["user_ids" => 418618, "fields" => "sex"]);
```