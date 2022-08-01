<?php

namespace Foundation;

use Astaroth\Debug\Dump;
use Astaroth\Foundation\ModifiedObject;
use Astaroth\Foundation\Reflect;
use Astaroth\Route\Attribute\AdditionalParameter;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsObject;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertTrue;

class ReflectTest extends TestCase
{

    private \ReflectionMethod $reflectionMethod;
    private array $reflectionParameter;

    protected function setUp(): void
    {
        $this->reflectionMethod = new \ReflectionMethod(ModifiedObject::class, 'replaceObjects');
        $this->reflectionParameter = $this->reflectionMethod->getParameters();
    }

    public function testInvoke()
    {
        $modifiedObject = new ModifiedObject();

        Reflect::invoke($modifiedObject, $this->reflectionMethod, [new \stdClass()]);

        assertCount(1, $modifiedObject->getReplaceableObjects());
    }

    public function testParameterNormalizer()
    {
        $params = Reflect::parameterNormalizer(
            $this->reflectionParameter,
            [new AdditionalParameter('object', false, new \stdClass())]
        );

        assertIsObject($params[0]);
    }

    public function testInstantiateClass()
    {
        $instance = Reflect::instantiateClass(Dump::class,
            new AdditionalParameter('mixed', false, new \stdClass())
        );
        /** @var Dump $instance */
        assertIsString($instance->return());
    }

    public function testMakeParameter()
    {
        $parameter = Reflect::makeParameter(
            new \stdClass(),
            false
        );
        assertTrue($parameter->getType() === \stdClass::class);
    }
}
