<?php
declare(strict_types=1);

namespace Foundation;

use Astaroth\Foundation\Executor;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Astaroth\Route\Attribute\ValidatedObject;
use Astaroth\Test\TestCase;
use Astaroth\Test\TestClass;
use ReflectionException;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class ExecutorTest extends TestCase
{

    public function benchLaunch(): void
    {
        $this->testLaunch();
    }
    
    /**
     * @throws ReflectionException
     */
    public function testLaunch(): void
    {

        $ev = new EventAttributeHandler([TestClass::class], $this->getTestData());
        foreach ($ev->validate() as $validatedObject) {
            assertEquals($validatedObject::class, ValidatedObject::class);

            (new Executor($validatedObject->getObject(), $validatedObject->getMethods(), [EventAttributeHandler::fetchData($this->getTestData())]))
                ->launch(static function ($methodResult) {
                    assertTrue($methodResult);
                });
        }
    }
}
