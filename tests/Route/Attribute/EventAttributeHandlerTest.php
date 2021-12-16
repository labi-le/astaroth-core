<?php

namespace Route\Attribute;

use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Astaroth\Route\Attribute\ValidatedObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use function PHPUnit\Framework\assertEquals;

class EventAttributeHandlerTest extends TestCase
{

    public function testFetchData(): void
    {
        assertEquals(
            EventAttributeHandler::fetchData((require __DIR__ . "/../../data.php"))::class,
            MessageNew::class
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testValidate(): void
    {
        require_once "testClass.php";

        $ev = new EventAttributeHandler([testClass::class], require __DIR__ . "/../../data.php");
        foreach ($ev->validate() as $validatedObject) {
            assertEquals($validatedObject::class, ValidatedObject::class);
        }
    }
}
