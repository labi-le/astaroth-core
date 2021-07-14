<?php


namespace Astaroth\Support\Facades\Wall;


use Astaroth\Interface\TokenChangeInterface;
use Astaroth\VkUtils\Contracts\IDocsUpload;
use Astaroth\VkUtils\Contracts\IPhoto;
use Astaroth\VkUtils\Contracts\IStories;
use Astaroth\VkUtils\Contracts\IVideo;

/**
 * Class WallUploaderFacade
 * @package Astaroth\Support\Facades\Wall
 */
class WallUploaderFacade extends \Astaroth\Support\Facades\Facade implements TokenChangeInterface
{
    private static string $access_token;

    public static function upload(IDocsUpload|IVideo|IPhoto|IStories ...$object): array
    {
        $uploader = static::getObject(\Astaroth\VkUtils\Uploading\WallUploader::class);
        self::$access_token === null ?: $uploader->setDefaultToken(self::$access_token);
        return $uploader->upload(...$object);
    }

    public static function changeToken(string $access_token): static
    {
        $singleton = new static();
        $singleton::$access_token = $access_token;
        return $singleton;
    }
}