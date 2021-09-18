<?php
declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Commands\BaseCommands;

/**
 * Garbage with which you can add placeholders to messages
 * @example hi %@name
 */
class Placeholder extends BaseCommands
{
    private const PATTERN = "/%(?:@?last-|(?:@?ful{2}-|@?))name/";


    //VK NAME *******************************************
    public const NAME = "name";

    public const LAST_NAME = "last_name";
    public const FIRST_NAME = "first_name";

    public const ID = "id";
    public const CLUB = "club";

    //END VK NAME *******************************************

    //TAG *******************************************************************
    public const NAME_TAG = "name";
    public const MENTION_NAME_TAG = self::MENTION . self::NAME_TAG;

    public const FULL_NAME_TAG = "full-name";
    public const MENTION_FULL_NAME_TAG = self::MENTION . self::FULL_NAME_TAG;

    public const LAST_NAME_TAG = "last-name";
    public const MENTION_LAST_NAME_TAG = self::MENTION . self::LAST_NAME_TAG;

    public const PERCENT = "%";
    public const STAR = "*";

    public const MENTION = self::PERCENT . "@";
    //END TAG *******************************************************************


    public function __construct(private string $subject){}

    /**
     * @throws \Throwable
     */
    public function replace(int $id): string
    {
        $member = $this->iterateId($id);
        $member_id = $member[self::ID];

        $member_name = $member[self::FIRST_NAME] ?? $member[self::NAME];
        $member_last_name = $member[self::LAST_NAME] ?? "";

        $member_full_name = trim("$member_name $member_last_name");

        $star_and_id_str = self::STAR . self::ID;
        $star_and_club_str = self::STAR . self::CLUB;
        return preg_replace_callback(self::PATTERN, static function ($match) use ($star_and_club_str, $star_and_id_str, $id, $member_id, $member_name, $member_last_name, $member_full_name) {
            return match (current($match)) {
                self::NAME_TAG => $member_name,
                self::MENTION_NAME_TAG => $id > 0
                    ? "$star_and_id_str$member_id($member_name)"
                    : "$star_and_club_str$member_id($member_name)",

                self::FULL_NAME_TAG => $member_full_name,
                self::MENTION_FULL_NAME_TAG => $id > 0
                    ? "$star_and_id_str$member_id($member_full_name)"
                    : "$star_and_club_str$member_id($member_name)",

                self::LAST_NAME_TAG => $id > 0 ? $member_last_name : $member_name,
                self::MENTION_LAST_NAME_TAG => $id > 0
                    ? "$star_and_id_str$member_id($member_last_name)"
                    : "$star_and_club_str$member_id($member_name)",

                default => throw new \UnhandledMatchError("No valid placeholder found")
            };
        }, $this->getSubject());
    }

    /**
     * @throws \Throwable
     */
    private function iterateId(int $id): array|false
    {
        if ($id > 0) {
            return current($this->usersGet($id));
        }
        return current($this->groupsGetById($id));
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }
}