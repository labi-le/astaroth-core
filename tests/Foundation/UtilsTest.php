<?php

declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Utils;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;

class UtilsTest extends TestCase
{

    public function testTransliteration()
    {
        assertEquals("aboba", Utils::transliteration("абоба"));
    }

    public function testMultiExplode()
    {
        $data = Utils::multiExplode([",", ";"], "i,love;php");
        assertArrayHasKey(0, $data);
        assertArrayHasKey(1, $data);
        assertArrayHasKey(2, $data);
    }

    public function testRegexId()
    {
        $str = "[id1|durov] [id418618|lbl] foo bar";
        $data = Utils::regexId($str);

        assertArrayHasKey(0, $data);
        assertArrayHasKey(1, $data);
    }

    public function testRemoveFirstWord()
    {
        $str = "i love world"; //12 symbol

        assertEquals(11, mb_strlen(Utils::removeFirstWord($str)));
    }
}
