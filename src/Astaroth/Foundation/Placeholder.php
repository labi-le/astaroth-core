<?php
declare(strict_types=1);

namespace Astaroth\Foundation;

use Astaroth\Support\Facades\Request;
use UnhandledMatchError;

/**
 * Garbage with which you can add placeholders to messages
 * @example hi %@name
 */
class Placeholder
{
    private const PATTERN = "/%(?:@?last-|(?:@?ful{2}-|@?))name/";


    //VK NAME *******************************************
    public const NAME = "name";
    public const LAST_NAME = "last_name";
    public const FIRST_NAME = "first_name";
    public const ID = "id";
    public const CLUB = "club";

    public const FULL_NAME = "full-name";

    //END VK NAME *******************************************

    //TAG *******************************************************************
    private const NAME_TAG = self::PERCENT . "name";
    private const MENTION_NAME_TAG = self::PERCENT . self::MENTION . self::NAME;

    private const FULL_NAME_TAG = self::PERCENT . "full-name";
    private const MENTION_FULL_NAME_TAG = self::PERCENT . self::MENTION . self::FULL_NAME;

    private const LAST_NAME_TAG = self::PERCENT . "last-name";
    private const MENTION_LAST_NAME_TAG = self::PERCENT . self::MENTION . self::LAST_NAME;
    //END TAG *******************************************************************

    private const PERCENT = "%";
    private const STAR = "*";
    private const MENTION = "@";

    private const STAR_AND_ID = self::STAR . self::ID;
    private const STAR_AND_CLUB = self::STAR . self::CLUB;


    public function __construct(private string $subject)
    {
    }

    /**
     * @throws \Throwable
     */
    public function replace(int $id): string
    {
        $member = $this->iterateId($id);
        $member_id = $member[self::ID];

        $member_name = $member[self::FIRST_NAME] ?? $member[self::NAME];
        $member_last_name = $member[self::LAST_NAME] ?? "";

        $member_full_name = \trim("$member_name $member_last_name");

        return \preg_replace_callback(self::PATTERN,
            static function ($match) use ($id, $member_id, $member_name, $member_last_name, $member_full_name) {
                return match (\current($match)) {
                    self::NAME_TAG => $member_name,
                    self::MENTION_NAME_TAG => $id > 0
                        ? self::STAR_AND_ID . "$member_id($member_name)"
                        : self::STAR_AND_CLUB . "$member_id($member_name)",

                    self::FULL_NAME_TAG => $member_full_name,
                    self::MENTION_FULL_NAME_TAG => $id > 0
                        ? self::STAR_AND_ID . "$member_id($member_full_name)"
                        : self::STAR_AND_CLUB . "$member_id($member_name)",

                    self::LAST_NAME_TAG => $id > 0 ? $member_last_name : $member_name,
                    self::MENTION_LAST_NAME_TAG => $id > 0
                        ? self::STAR_AND_ID . "$member_id($member_last_name)"
                        : self::STAR_AND_CLUB . "$member_id($member_name)",

                    default => throw new UnhandledMatchError("No valid placeholder found")
                };
            },
            $this->getSubject());
    }

    /**
     * @throws \Throwable
     */
    private function iterateId(int $id): array|false
    {
        if ($id > 0) {
            return \current(Request::call("users.get", ["user_ids" => $id, "name_case" => "nom"]));
        }
        return \current(Request::call("groups.getById", ["group_ids" => $id]));
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }
}