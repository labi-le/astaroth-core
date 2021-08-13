<?php
declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Commands\BaseCommands;
use Astaroth\Support\Facades\RequestFacade;

/**
 * Херня с помощью которой можно добавить динамики в сообщениях
 * @example  приветик %@name
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

    public static array $tag =
        [
            self::NAME => "%name", self::MENTION_NAME => "%@name",
            self::FULL_NAME => "%full-name", self::MENTION_FULL_NAME => "%@full-name",
            self::LAST_NAME => "%last-name", self::MENTION_LAST_NAME => "%@last-name"
        ];

    public function __construct(private string $subject)
    {
    }

    public function replace(int $id): string
    {
        $member = $this->iterateId($id);
        $member_id = $member["id"];

        $member_name = $member["first_name"] ?? $member["name"];
        $member_last_name = $member["last_name"] ?? "";

        $member_full_name = trim("$member_name $member_last_name");

        return preg_replace_callback(self::PATTERN, static function ($match) use ($id, $member_id, $member_name, $member_last_name, $member_full_name) {
            Utils::var_dumpToStdout($match);
            return match (current($match)) {
                self::$tag[self::NAME] => $member_name,
                self::$tag[self::MENTION_NAME] => $id > 0 ? "*id$member_id($member_name)" : "*club$member_id($member_name)",

                self::$tag[self::FULL_NAME] => $member_full_name,
                self::$tag[self::MENTION_FULL_NAME] => $id > 0 ? "*id$member_id($member_full_name)" : "*club$member_id($member_name)",

                self::$tag[self::LAST_NAME] => $id > 0 ? $member_last_name : $member_name,
                self::$tag[self::MENTION_LAST_NAME] => $id > 0 ? "*id$member_id($member_last_name)" : "*club$member_id($member_name)",
            };
        }, $this->subject);
    }

    private function iterateId(int $id): array|false
    {
        if ($id > 0) {
            return current($this->usersGet($id));
        }
        return current($this->groupsGetById($id));
    }
}