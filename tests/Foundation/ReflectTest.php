<?php
declare(strict_types=1);

namespace Foundation;

use Astaroth\Debug\Dump;
use Astaroth\Foundation\ModifiedObject;
use Astaroth\Foundation\Parameter;
use Astaroth\Foundation\Reflect;
use ReflectionMethod;
use stdClass;
use Astaroth\Test\TestCase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertIsObject;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertTrue;

class ReflectTest extends TestCase
{

    private ReflectionMethod $reflectionMethod;
    private array $reflectionParameter;

    protected function setUp(): void
    {
        $this->reflectionMethod = new ReflectionMethod(ModifiedObject::class, 'replaceObjects');
        $this->reflectionParameter = $this->reflectionMethod->getParameters();
    }

    public function testInvoke(): void
    {
        $modifiedObject = new ModifiedObject();

        Reflect::invoke($modifiedObject, $this->reflectionMethod, [new stdClass()]);

        assertCount(1, $modifiedObject->getReplaceableObjects());
    }

    public function testParameterNormalizer(): void
    {
        $params = Reflect::parameterNormalizer(
            $this->reflectionParameter,
            [new Parameter('object', false, new stdClass())]
        );

        assertIsObject($params[0]);
    }

    public function testInstantiateClass(): void
    {
        $instance = Reflect::instantiateClass(Dump::class,
            new Parameter('mixed', false, new stdClass())
        );
        /** @var Dump $instance */
        assertIsString($instance->return());
    }

    public function testMakeParameter(): void
    {
        $parameter = Reflect::makeParameter(
            new stdClass(),
            false
        );
        assertTrue($parameter->getType() === stdClass::class);
    }
}
