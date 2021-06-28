<?php


namespace Astaroth\Support\Facades\Message;


use Astaroth\VkUtils\Contracts\IDocsUpload;
use Astaroth\VkUtils\Contracts\IPhoto;
use Astaroth\VkUtils\Contracts\IStories;
use Astaroth\VkUtils\Contracts\IVideo;

/**
 * Class MessageUploaderFacade
 * @package Astaroth\Support\Facades\Message
 */
class MessageUploaderFacade extends \Astaroth\Support\Facades\Facade
{
    private static string $access_token;

    public static function upload(IDocsUpload|IVideo|IPhoto|IStories ...$object): array
    {
        $uploader = static::getObject(\Astaroth\VkUtils\Uploading\MessagesUploader::class);
        self::$access_token === null ?: $uploader->setDefaultToken(self::$access_token);

        return $uploader->upload(...$object);
    }

    public static function changeToken(string $access_token): void
    {
        self::$access_token = $access_token;
    }
}