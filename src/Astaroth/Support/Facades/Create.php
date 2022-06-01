<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;


use Astaroth\Containers\BuilderContainer;
use Astaroth\Foundation\Placeholder;
use Astaroth\VkUtils\Builder;
use Astaroth\VkUtils\Contracts\IBuilder;
use Astaroth\VkUtils\Contracts\IMessageBuilder;
use Throwable;
use function array_map;

/**
 * Class Create
 * @package Astaroth\Support\Facades\Create
 * @psalm-suppress MixedReturnStatement
 *
 */
final class Create extends AbstractFacade
{

    protected static function getContainerService(): Builder
    {
        /** @var Builder $container */
        $container = parent::getContainerService();
        return $container;
    }

    protected static function getServiceName(): string
    {
        return BuilderContainer::CONTAINER_ID;
    }

    /**
     * We check the message for placeholders and, if necessary, add
     * @param IBuilder ...$instances
     * @return IBuilder[]
     * @throws Throwable
     */
    private static function messagePlaceholder(IBuilder ...$instances): array
    {
        return array_map(static function (IBuilder $instance) {
            if ($instance instanceof IMessageBuilder) {
                $message = (string)$instance->getParams()["message"];
                $id = (int)$instance->getParams()["peer_ids"] > 2e9
                    ? (int)$instance->getParams()["user_ids"]
                    : (int)$instance->getParams()["peer_ids"];
                if (!empty($message)) {
                    return $instance->setMessage((new Placeholder($message))->replace($id));
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
        return self::getContainerService()->create(...$new_instance);
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
        return clone self::getContainerService()->setDefaultToken($access_token);
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