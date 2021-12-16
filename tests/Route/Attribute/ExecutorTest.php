<?php

namespace Route\Attribute;

use Astaroth\Foundation\Executor;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Astaroth\Route\Attribute\ValidatedObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ExecutorTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testLaunch(): void
    {
        require_once "testClass.php";

        $data = require __DIR__ . "/../../data.php";
        $ev = new EventAttributeHandler([testClass::class], $data);
        foreach ($ev->validate() as $validatedObject) {
            assertEquals($validatedObject::class, ValidatedObject::class);

            (new Executor($validatedObject->getObject(), $validatedObject->getMethods()))
                ->replaceObjects(EventAttributeHandler::fetchData($data))
                ->launch(static function ($methodResult) {
                    assertTrue($methodResult);
                });
        }
    }
}
