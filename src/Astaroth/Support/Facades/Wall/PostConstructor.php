<?php


namespace Astaroth\Support\Facades\Wall;

use Astaroth\VkUtils\Builders\PostBuilder;

/**
 * Class CreatePost
 * @package Astaroth\Support\Facades\Wall
 * @see
 */
class PostConstructor extends \Astaroth\Support\Facades\Facade
{
    public static function create(callable $post): array
    {
        return static::getObject(\Astaroth\VkUtils\Post::class)->create($post(new PostBuilder()));
    }
}