<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Placeholder;
use PHPUnit\Framework\TestCase;
use Throwable;
use function PHPUnit\Framework\assertNotEmpty;

class PlaceholderTest extends TestCase
{
    public const LAST_NAME = "last_name";
    public const FIRST_NAME = "first_name";

    /**
     * Class with which you can add placeholders to messages
     *
     * @throws Throwable
     *
     * @example FirstName - %name
     * @example Name with mention "%@name"
     * @example FirstName LastName "%full-name"
     * @example FirstName LastName mention "%@full-name"
     * @example LastName "%last-name"
     * @example LastName mention "%@last-name"
     * @example hi %@name
     */
    public function testReplace(): void
    {
        $p = new Placeholder("%name");

        assertNotEmpty($p->replace(1));
        assertNotEmpty($p->replace(-1));


        $p = new Placeholder("%@name");
        assertNotEmpty($p->replace(1));
        assertNotEmpty($p->replace(-1));


        $p = new Placeholder("%full-name");
        assertNotEmpty($p->replace(1));
        assertNotEmpty($p->replace(-1));


        $p = new Placeholder("%@full-name");
        assertNotEmpty($p->replace(1));
        assertNotEmpty($p->replace(-1));


        $p = new Placeholder("%last-name");
        assertNotEmpty($p->replace(1));
        assertNotEmpty($p->replace(-1));


        $p = new Placeholder("%@last-name");
        assertNotEmpty($p->replace(1));
        assertNotEmpty($p->replace(-1));

    }
}
