<?php
declare(strict_types=1);

namespace Route\Attribute;

use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Route\Attribute\EventAttributeHandler;
use Astaroth\Route\Attribute\ValidatedObject;
use Astaroth\Test\TestClass;
use ReflectionException;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertEquals;

class EventAttributeHandlerTest extends TestCase
{

    public function testFetchData(): void
    {
        assertEquals(
            EventAttributeHandler::fetchData($this->getTestData())::class,
            MessageNew::class
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testValidate(): void
    {
        $ev = new EventAttributeHandler([TestClass::class], $this->getTestData());
        foreach ($ev->validate() as $validatedObject) {
            assertEquals($validatedObject::class, ValidatedObject::class);
        }
    }
}
