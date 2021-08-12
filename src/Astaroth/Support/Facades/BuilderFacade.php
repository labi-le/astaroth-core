<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\VkUtils\Builder;
use Astaroth\VkUtils\Contracts\IBuilder;

/**
 * Class BuilderFacade
 * @package Astaroth\Support\Facades\Message
 */
final class BuilderFacade
{
    private const SERVICE_ID = "builder";

    public static function create(IBuilder ...$func): array
    {
        return FacadePlaceholder::getInstance()->getContainer()
            ?->get(self::SERVICE_ID)
            ?->create(...$func);
    }

    /**
     * Get an instance with a different token
     * @param string $access_token
     * @return Builder
     */
    public static function changeToken(string $access_token): Builder
    {
        /**
         * @var $instance Builder
         */
        $instance = clone FacadePlaceholder::getInstance()->getContainer()->get(self::SERVICE_ID);
        return $instance->setDefaultToken($access_token);
    }

}