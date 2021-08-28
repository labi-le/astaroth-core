<?php
declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Commands\BaseCommands;

/**
 * Garbage with which you can add placeholders to messages
 * @example hi %@name
 */
final class Placeholder extends BaseCommands
{
    private const PATTERN = "/%(?:@?last-|(?:@?ful{2}-|@?))name/";

    public const NAME = "name";
    public const MENTION_NAME = "mention_name";

    public const FULL_NAME = "full_name";
    public const MENTION_FULL_NAME = "mention_full_name";

    public const LAST_NAME = "last_name";
    public const MENTION_LAST_NAME = "mention_last_name";

    public const PERCENT = "%";
    public const STAR = "*";
    public const MENTION = self::PERCENT . "%";

    public const ID = "id";
    public const CLUB = "club";

    public const FIRST_NAME = "first_name";

    public static array $tag =
        [
            self::NAME => self::PERCENT . self::NAME,
            self::MENTION_NAME => self::MENTION . self::NAME,

            self::FULL_NAME => self::PERCENT . self::FULL_NAME,
            self::MENTION_FULL_NAME => self::MENTION . self::FULL_NAME,

            self::LAST_NAME => self::PERCENT . self::LAST_NAME,
            self::MENTION_LAST_NAME => self::MENTION . self::LAST_NAME
        ];

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
                self::$tag[self::NAME] => $member_name,
                self::$tag[self::MENTION_NAME] => $id > 0
                    ? "$star_and_id_str$member_id($member_name)"
                    : "$star_and_club_str$member_id($member_name)",

                self::$tag[self::FULL_NAME] => $member_full_name,
                self::$tag[self::MENTION_FULL_NAME] => $id > 0
                    ? "$star_and_id_str$member_id($member_full_name)"
                    : "$star_and_club_str$member_id($member_name)",

                self::$tag[self::LAST_NAME] => $id > 0 ? $member_last_name : $member_name,
                self::$tag[self::MENTION_LAST_NAME] => $id > 0
                    ? "$star_and_id_str$member_id($member_last_name)"
                    : "$star_and_club_str$member_id($member_name)",
            };
        }, $this->subject);
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
}