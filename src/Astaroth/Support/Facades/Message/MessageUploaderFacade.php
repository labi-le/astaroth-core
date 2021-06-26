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
    public static function upload(IDocsUpload|IVideo|IPhoto|IStories ...$object): array
    {
        return static::getObject(\Astaroth\VkUtils\Uploading\MessagesUploader::class)->upload(...$object);
    }
}