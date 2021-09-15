<?php

declare(strict_types=1);

namespace Astaroth\Attribute\Refactor;

use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Route\DataTransferObject\MethodInfo;
use Astaroth\Route\DataTransferObject\MethodParamInfo;
use Astaroth\Route\ReturnResultHandler;

class Execute
{
    public const AVAILABLE_EVENTS =
        [
            MessageNew::class,
            MessageEvent::class
        ];

    /** @var object[] */
    private array $extraParameters = [];


    /**
     * General event coordinator
     * @param object $instance
     * @param MethodInfo[] $methods
     * @param \Closure $event
     */
    public function __construct(
        private object   $instance,
        private array    $methods,
        private \Closure $event,
    )
    {
    }

    /**
     * We call methods from the class on which the correct route is set
     * And add arguments
     * method_exist is not needed since method 100% exists
     * @param object $instance
     * @param string $method
     * @return mixed
     */
    private function execute(object $instance, string $method): mixed
    {
        return $instance->$method(...$this->getExtraParameters());
    }

    public function launch(): void
    {
        foreach ($this->methods as $method) {
            foreach ($method->getAttribute() as $attribute) {
                if ($this->runEvent($attribute)) {

                    $this->parameterInitializer($method->getParameters());
                    $method_return = $this->execute($this->instance, $method->getName());

                    /** We process the result returned by the method */
                    new ReturnResultHandler($method_return);

                    break;
                }
            }
        }
    }

    private function runEvent(object $attribute): bool
    {
        return ($this->event)($attribute);
    }

    /**
     * Adds the necessary parameters to the method that requires it
     * @param MethodParamInfo[] $methodParams
     * @return void
     */
    private function parameterInitializer(array $methodParams): void
    {
        foreach ($methodParams as $parameter) {
            if (
                in_array($parameter->getType(), self::AVAILABLE_EVENTS, true) === false
                && class_exists($parameter->getType())
                && in_array($parameter, $this->getExtraParameters(), true) === false
            ) {
                $this->addExtraParameters(new ($parameter->getType()));
            }
        }
    }


    /**
     * @return object[]
     */
    public function getExtraParameters(): array
    {
        return $this->extraParameters;
    }

    /**
     * @param object $instance
     * @return static
     */
    public function addExtraParameters(object $instance): static
    {
        $this->extraParameters[] = $instance;
        return $this;
    }
}