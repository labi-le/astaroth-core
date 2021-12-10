<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\Containers\BuilderContainer;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Placeholder;
use Astaroth\VkUtils\Builder;
use Astaroth\VkUtils\Contracts\IBuilder;
use Astaroth\VkUtils\Contracts\IMessageBuilder;
use Throwable;
use function array_map;

/**
 * Class Create
 * @package Astaroth\Support\Facades\Create
 */
final class Create
{
    /**
     * We check the message for placeholders and, if necessary, add
     * @param IBuilder ...$instances
     * @return array
     * @throws Throwable
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
     * Create instance
     * @param IBuilder ...$instance
     * @return array
     * @throws Throwable
     * @psalm-suppress NullableReturnStatement
     */
    public static function new(IBuilder ...$instance): array
    {
        $new_instance = self::messagePlaceholder(...$instance);

        return FacadePlaceholder::getInstance()
            ->getContainer()->get(BuilderContainer::CONTAINER_ID)
            ?->create(...$new_instance);
    }

    /**
     * Get an instance with a different token
     * @param string $access_token
     * @return Builder
     *
     * @psalm-suppress PossiblyInvalidClone
     */
    public static function changeToken(string $access_token): Builder
    {
        /**
         * @var Builder $instance
         */
        $instance = clone FacadePlaceholder::getInstance()->getContainer()->get(BuilderContainer::CONTAINER_ID);
        return $instance->setDefaultToken($access_token);
    }

    /**
     * @param IBuilder ...$instance
     * @return array
     * @throws Throwable
     */
    public function __invoke(IBuilder ...$instance): array
    {
        return self::new(...$instance);
    }

}