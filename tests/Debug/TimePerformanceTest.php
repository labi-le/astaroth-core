<?php
declare(strict_types=1);

namespace Debug;

use Astaroth\Debug\TimePerformance;
use PHPUnit\Framework\TestCase;
use function count;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertNotNull;

class TimePerformanceTest extends TestCase
{

    public function testGetStat()
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
            sleep(2);
        };

        assertNotNull((new TimePerformance($app))->getTimeEnd());
    }
}
