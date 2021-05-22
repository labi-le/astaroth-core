<?php

declare(strict_types=1);

namespace Bot;

use Astaroth\CallBack\CallBack;
use Bot\Controller\Controller;
use Bot\Models\Auth;
use Bot\Models\ConfigFile;
use Bot\Models\DataParser;

final class bootstrap
{
    public const SIMILAR_PERCENT = 80;
    private array $file;

    private function __construct(string $path)
    {
        $this->checkPhpVersion();
        $this->file = ConfigFile::open($path);
    }

    private function checkPhpVersion(): void
    {
        PHP_MAJOR_VERSION >= 8 ?: die('Версия PHP ниже 8, обновляйся');
    }

    public static function setConfigFile(string $path): bootstrap
    {
        return new self($path);
    }

    /**
     * Запустить бота
     */
    public function run(): void
    {
        $this->checkPhpVersion();
        $config = $this->file;

        $type = $config['type'];
        if ($type === 'callback') {
            $this->callback($config);
        } elseif ($type === 'longpoll') {
            $this->longpoll($config['auth']);
        }

    }

    /**
     * Запустить бота в режиме коллбэк
     * @param array $config
     */
    private function callback(array $config): void
    {
        $auth = $config['auth'];
        $clients = $this->createAuth($auth['token'], $auth['v']);

        unset($clients['longpoll']);

        if ($auth['secret'] !== false) {
            $secret = null;
        } else {
            $secret = $auth['secret'];
        }
        $callback = new CallBack($auth['confirmation'], $secret, $config['handle_repeated_requests']);

        Controller::setClient(...$clients);
        (new Controller)
            ->handle(new DataParser($callback->getData()));
    }

    /**
     * Запустить бота в режиме лонгпулл
     * @param array $auth
     */
    private function longpoll(array $auth): void
    {
        $clients = $this->createAuth($auth['token'], $auth['v']);
        $longpoll = $clients['longpoll'];
        unset($clients['longpoll']);

        Controller::setClient(...$clients);
        $longpoll->listen(static function ($data) {
            (new Controller)
                ->handle(new DataParser($data));
        });
    }

    public function createAuth(string $token, string|int $version): array
    {
        return (new Auth($token, $version))->getClients();
    }
}