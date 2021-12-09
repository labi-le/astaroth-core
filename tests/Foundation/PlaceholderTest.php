<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Placeholder;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class PlaceholderTest extends TestCase
{
    public const LAST_NAME = "last_name";
    public const FIRST_NAME = "first_name";

    /**
     * Class with which you can add placeholders to messages
     *
     * @throws \Throwable
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
        assertEquals("Павел", $p->replace(1));
        assertEquals("ВКонтакте API", $p->replace(-1));


        $p = new Placeholder("%@name");
        assertEquals("*id1(Павел)", $p->replace(1));
        assertEquals("*club1(ВКонтакте API)", $p->replace(-1));


        $p = new Placeholder("%full-name");
        assertEquals("Павел Дуров", $p->replace(1));
        assertEquals("ВКонтакте API", $p->replace(-1));


        $p = new Placeholder("%@full-name");
        assertEquals("*id1(Павел Дуров)", $p->replace(1));
        assertEquals("*club1(ВКонтакте API)", $p->replace(-1));


        $p = new Placeholder("%last-name");
        assertEquals("Дуров", $p->replace(1));
        assertEquals("ВКонтакте API", $p->replace(-1));


        $p = new Placeholder("%@last-name");
        assertEquals("*id1(Дуров)", $p->replace(1));
        assertEquals("*club1(ВКонтакте API)", $p->replace(-1));

    }
}
