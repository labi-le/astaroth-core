<?php
declare(strict_types=1);

namespace Debug;

use Astaroth\Debug\Memory;
use Astaroth\Test\TestCase;

use function mt_rand;
use function PHPUnit\Framework\assertEquals;
use function count;
use function PHPUnit\Framework\assertIsInt;
use function range;
use function uniqid;

class MemoryTest extends TestCase
{

    public function bench(): void
    {
        $this->testGetStat();
    }

    public function testGetStat(): void
    {
        $app = static function () {
            $arr = [];
            $arr2 = [];
            foreach (range(0, 10000) as $ignored) {
                $arr[] = mt_rand();
                $arr2[] = uniqid('', true);
            }

            assertEquals(10001, count($arr));
            assertEquals(10001, count($arr2));
        };

        assertIsInt((new Memory($app))->getFinalMemoryUsage());
    }
}
