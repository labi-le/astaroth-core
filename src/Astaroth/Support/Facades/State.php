<?php

declare(strict_types=1);

namespace Astaroth\Support\Facades;

final class State
{
    public function __invoke(int $member_id, string $state_name): bool
    {
        return self::_add($member_id, $state_name);
    }

    private static function _add(int $member_id, string $state_name): bool
    {
        return (new Session($member_id, \Astaroth\Attribute\General\State::RESERVED_NAME))
            ->put($state_name, true);
    }

    public function __construct(int $member_id, string $state_name)
    {
        self::_add($member_id, $state_name);
    }

    public static function add(int $member_id, string $state_name): bool
    {
        return self::_add($member_id, $state_name);
    }

    public static function remove(int $member_id, string $state_name): bool
    {
        return (new Session($member_id, \Astaroth\Attribute\General\State::RESERVED_NAME))
            ->removeKey($state_name);
    }
}
