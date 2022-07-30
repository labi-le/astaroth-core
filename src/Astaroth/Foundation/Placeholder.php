<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use Throwable;
use UnhandledMatchError;

use function abs;
use function current;
use function explode;
use function file_get_contents;
use function preg_match;
use function preg_replace_callback;
use function trim;

/**
 * Class with which you can add placeholders to messages
 */
final class Placeholder
{
    private const PATTERN = "/%(?:@?last-|(?:@?ful{2}-|@?))name/";


    //VK NAME *******************************************
    public const
        NAME = "name",
    LAST_NAME_VK = "last_name",
    FIRST_NAME_VK = "first_name",
    ID = "id",
    CLUB = "club";


    public const
        FULL_NAME_PH = "full-name",
    LAST_NAME_PH = "last-name";

    //END VK NAME *******************************************

    //TAG *******************************************************************
    private const
        NAME_TAG = self::PERCENT . self::NAME,
    MENTION_NAME_TAG = self::PERCENT . self::MENTION . self::NAME;

    private const
        FULL_NAME_TAG = self::PERCENT . self::FULL_NAME_PH,
    MENTION_FULL_NAME_TAG = self::PERCENT . self::MENTION . self::FULL_NAME_PH;

    private const
        LAST_NAME_TAG = self::PERCENT . self::LAST_NAME_PH,
    MENTION_LAST_NAME_TAG = self::PERCENT . self::MENTION . self::LAST_NAME_PH;
    //END TAG *******************************************************************

    private const
        PERCENT = "%",
    STAR = "*",
    MENTION = "@",

    STAR_AND_ID = self::STAR . self::ID,
    STAR_AND_CLUB = self::STAR . self::CLUB;


    /**
     * @param string $subject
     *
     * @example Name with mention %@name
     * @example FirstName LastName %full-name
     * @example FirstName LastName mention %@full-name
     * @example LastName %last-name
     * @example LastName mention %@last-name
     * @example hi %@name
     * @example FirstName  %name
     */
    public function __construct(private readonly string $subject)
    {
    }

    /**
     * @throws Throwable
     * @psalm-suppress MixedArgumentTypeCoercion, MixedAssignment
     */
    public function replace(int $id): string
    {
        return preg_replace_callback(
            self::PATTERN,
            static function ($match) use ($id) {
                $member = self::iterateId($id);
                $member_id = $id;

                $member_name = $member[self::FIRST_NAME_VK] ?? $member[self::NAME];
                $member_last_name = $member[self::LAST_NAME_VK] ?? "";

                $member_full_name = trim("$member_name $member_last_name");

                return match (current($match)) {
                    self::NAME_TAG => $member_name,
                    self::MENTION_NAME_TAG => $id > 0
                        ? self::STAR_AND_ID . "$member_id($member_name)"
                        : self::STAR_AND_CLUB . abs($member_id) . "($member_name)",

                    self::FULL_NAME_TAG => $member_full_name,
                    self::MENTION_FULL_NAME_TAG => $id > 0
                        ? self::STAR_AND_ID . "$member_id($member_full_name)"
                        : self::STAR_AND_CLUB . abs($member_id) . "($member_name)",

                    self::LAST_NAME_TAG => $id > 0 ? $member_last_name : $member_name,
                    self::MENTION_LAST_NAME_TAG => $id > 0
                        ? self::STAR_AND_ID . "$member_id($member_last_name)"
                        : self::STAR_AND_CLUB . abs($member_id) . "($member_name)",

                    default => throw new UnhandledMatchError("No valid placeholder found")
                };
            },
            $this->getSubject()
        );
    }

    /**
     * @throws Throwable
     */
    private static function iterateId(int $id): array
    {
        /** @noinspection RegExpRedundantEscape */
        preg_match(
            '/\<foaf\:name\>(.*)\<\/foaf\:name\>/m',
            file_get_contents("https://vk.com/foaf.php?id=$id"),
            $data
        );

        $site = mb_convert_encoding($data[1], "UTF-8", "windows-1251");


        if ($id > 0) {
            $e = explode(" ", $site);
            return [
                self::FIRST_NAME_VK => $e[0],
                self::LAST_NAME_VK => $e[1],
            ];
        }

        return [self::NAME => $site];
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }
}
