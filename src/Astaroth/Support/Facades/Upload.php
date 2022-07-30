<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

use Astaroth\Containers\UploaderContainer;
use Astaroth\VkUtils\Contracts\ICanBeSaved;
use Astaroth\VkUtils\Uploader;
use Exception;

final class Upload extends AbstractFacade
{
    /**
     * @throws Exception
     */
    public function __invoke(ICanBeSaved ...$instance): array
    {
        return self::attachments(...$instance);
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public static function attachments(ICanBeSaved ...$instance): array
    {
        return self::getContainerService()->upload(...$instance);
    }

    /**
     * @param string $access_token
     * @return Uploader
     */
    public static function changeToken(string $access_token): Uploader
    {
        return clone self::getContainerService()->setDefaultToken($access_token);
    }

    protected static function getServiceName(): string
    {
        return UploaderContainer::CONTAINER_ID;
    }

    protected static function getContainerService(): Uploader
    {
        /**
         * @var Uploader $container
         */
        $container = parent::getContainerService();

        return $container;
    }
}
