<?php
declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Containers\UploaderContainerInterface;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\VkUtils\Contracts\ICanBeSaved;
use Astaroth\VkUtils\Uploader;

final class Upload
{
    /**
     * @throws \Exception
     */
    public function __invoke(ICanBeSaved ...$instance): array
    {
        return self::attachments(...$instance);
    }

    /**
     * @param ICanBeSaved ...$instance
     * @return array
     * @throws \Exception
     */
    public static function attachments(ICanBeSaved ...$instance): array
    {
        /**
         * @var Uploader $facade
         */
        $facade = FacadePlaceholder::getInstance()->getContainer()->get(UploaderContainerInterface::SERVICE_ID);
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
        $instance = FacadePlaceholder::getInstance()->getContainer()->get(UploaderContainerInterface::SERVICE_ID);

        return clone $instance->setDefaultToken($access_token);
    }
}