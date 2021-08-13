<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\Foundation\Placeholder;
use Astaroth\VkUtils\Builder;
use Astaroth\VkUtils\Contracts\IBuilder;
use Astaroth\VkUtils\Contracts\IMessageBuilder;

/**
 * Class BuilderFacade
 * @package Astaroth\Support\Facades\Message
 */
final class BuilderFacade
{
    private const SERVICE_ID = "builder";

    /**
     * We check the message for placeholders and, if necessary, add
     * @param IBuilder ...$instances
     * @return array
     */
    private static function messagePlaceholder(IBuilder ...$instances): array
    {
        return array_map(static function (IBuilder $instance) {
            if ($instance instanceof IMessageBuilder) {
                $message = $instance->getParams()["message"];
                $id = $instance->getParams()["peer_ids"] > 2e9 ? $instance->getParams()["user_ids"] : $instance->getParams()["peer_ids"];
                if (!empty($message)) {
                    return $instance->setMessage((new Placeholder($message))->replace((int)$id));
                }
            }
            return $instance;
        }, $instances);
    }

    /**
     * BuilderFacade create instance
     * @param IBuilder ...$func
     * @return array
     */
    public static function create(IBuilder ...$func): array
    {
        $new_func = self::messagePlaceholder(...$func);
        return FacadePlaceholder::getInstance()->getContainer()
            ?->get(self::SERVICE_ID)
            ?->create(...$new_func);
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