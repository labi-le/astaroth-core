# Astaroth

[![GitHub license](https://img.shields.io/badge/license-MIT-green.svg)](https://github.com/labi-le/astaroth-core/blob/main/LICENSE)
[![Packagist Stars](https://img.shields.io/packagist/stars/labile/astaroth-core)](https://packagist.org/packages/labile/astaroth-core/stats)
[![Download Stats](https://img.shields.io/packagist/dt/labile/astaroth-core)](https://packagist.org/packages/labile/astaroth-core/stats)
![Php requirement](https://img.shields.io/packagist/php-v/labile/astaroth-core)

Личный фреймворк для создания ботов

___

## Содержание

1. Конфигурация
    + [Установка](#Installation)
    + [Требования](#Requirement)
    + [Содержание env](#Env)
2. Примеры и документация
    + [Примеры](doc/example)
    + [Документация по аттрибутам](doc/attribute.md)
    + [Документация по фасадам](doc/facade.md)
    + [Документация по Utils](doc/utils.md)

___

### Installation

```
composer create-project labile/astaroth-framework bot
```

### Requirement

> Платформа - `Linux`\
> Версия `PHP` - `>=8`\
> Расширения: `ext-pcntl`, `ext-posix`, `mbstring`\
> Веб сервер (callback) - для дебага - нативный php, поднимается командой `composer serve` (php -S x.x.x.x)\
> Работает nginx, apache не проверял

### Env

```dotenv
APP_NAMESPACE = App\Command
ACCESS_TOKEN=slkaojdwiwajdowadjwa
TYPE=CALLBACK
API_VERSION=5.130
CONFIRMATION_KEY=2f21ed85
SECRET_KEY=
HANDLE_REPEATED_REQUESTS=
```

### Auth:

    ACCESS_TOKEN - access_token сообщества или пользователя
    API_VERSION - версия vk api
    CONFIRMATION_KEY - строка, которую должен вернуть сервер для события confirmation
    SECRET_KEY - произвольная строка, которая будет передаваться с каждым запросом (необязательный параметр)

### Остальные параметры:

    TYPE - тип работы бота. Возможны только два типа - CALLBACK, LONGPOLL
