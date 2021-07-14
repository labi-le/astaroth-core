<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades\Wall;


/**
 * Class CreatePost
 * @package Astaroth\Support\Facades\Wall
 * @see
 */
class PostConstructor extends \Astaroth\Support\Facades\Facade
{
    public static function create(callable $post): array
    {
        return static::getObject("post")
            ?->setDefaultToken(self::$container->getParameter("USER_ACCESS_TOKEN"))
            ->create($post(new \Astaroth\VkUtils\Builders\PostBuilder()));
    }
}