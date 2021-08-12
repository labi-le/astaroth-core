<?php
declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\VkUtils\Contracts\ICanBeSaved;
use Astaroth\VkUtils\Uploader;

final class UploaderFacade
{
    private const SERVICE_ID = "builder";

    /**
     * @param ICanBeSaved ...$CompatibilityInstances
     * @return array
     * @throws \Exception
     */
    public static function upload(ICanBeSaved ...$CompatibilityInstances): array
    {
        /**
         * @var $instance Uploader
         */
        $instance = Facade::getInstance()->getContainer()->get(self::SERVICE_ID);
        return $instance->upload(...$CompatibilityInstances);
    }

    /**
     * @param string $access_token
     * @return Uploader
     */
    public static function changeToken(string $access_token): Uploader
    {
        /**
         * @var $instance Uploader
         */
        $instance = Facade::getInstance()->getContainer()->get(self::SERVICE_ID);

        return clone $instance->setDefaultToken($access_token);
    }
}