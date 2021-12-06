<?php
declare(strict_types=1);

namespace Route\Attribute;

require_once "testClass.php";

use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Route\Attribute\EventDispatcher;
use Astaroth\Route\Attribute\Executor;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class MethodExecutorTest extends TestCase
{
    private Executor $methodExecutor;
    private MessageNew $data;

    protected function setUp(): void
    {
        $this->data = (require __DIR__ . "/../../data.php")->messageNew();
        $this->methodExecutor = new Executor(new ReflectionClass(testClass::class));

        $this->methodExecutor
            ->setCallableValidateAttribute(function ($attribute) {
                if ($attribute instanceof AttributeValidatorInterface) {
                    return $attribute->setHaystack(EventDispatcher::fetchData($this->data))->validate();
                }
                return false;
            })
            ->replaceObjects($this->data);
    }

    public function testLaunch()
    {
        $this->methodExecutor->launch();
    }
}