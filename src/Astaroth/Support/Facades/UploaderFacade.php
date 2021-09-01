<?php
declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\VkUtils\Contracts\ICanBeSaved;
use Astaroth\VkUtils\Uploader;

final class UploaderFacade
{
    private const SERVICE_ID = "uploader";

    /**
     * @param ICanBeSaved ...$instance
     * @return array
     * @throws \Exception
     */
    public static function upload(ICanBeSaved ...$instance): array
    {
        /**
         * @var Uploader $facade
         */
        $facade = FacadePlaceholder::getInstance()->getContainer()->get(self::SERVICE_ID);
        return $facade->upload(...$instance);
    }

    /**
     * @param string $access_token
     * @return Uploader
     */
    public static function changeToken(string $access_token): Uploader
    {
        /**
         * @var Uploader $instance
         */
        $instance = FacadePlaceholder::getInstance()->getContainer()->get(self::SERVICE_ID);

        return clone $instance->setDefaultToken($access_token);
    }
}