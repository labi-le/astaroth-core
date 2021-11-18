<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Auth\Configuration;
use Astaroth\Contracts\AttributeReturnInterface;
use Astaroth\Parser\ClassNotFoundException;
use Astaroth\Parser\DataTransferObject\MethodParamInfo;
use Astaroth\Parser\DataTransferObject\MethodsInfo;
use Astaroth\Route\ReturnResultHandler;

class MethodExecutor
{
    private const FORWARDED_PARAMETER = "__forwarded_parameter";

    /** @var AdditionalParameter[] */
    private array $extraParameters = [];

    /** @var AdditionalParameter[] */
    private array $parameters = [];
    private \Closure $callableValidateAttribute;


    /**
     * General event coordinator
     * @param string $className
     * @param MethodsInfo $methodsInfo DTO
     *
     * @see execute()
     */
    public function __construct(
        private string      $className,
        private MethodsInfo $methodsInfo,
    )
    {
    }

    /**
     * @throws ClassNotFoundException
     */
    public function launch(): void
    {
        foreach ($this->methodsInfo as $method) {
            foreach ($method->getAttribute() as $attribute) {
                //if the validation is successful, proceed to the execution of the method
                if ($this->validateAttribute($attribute)) {
                    //passing attributes to parameters (if their type is explicitly specified in user-method)
                    $this->addExtraAttributeToParameters($method->getAttribute());

                    $this->addParameters(...
                        array_map(static function (MethodParamInfo $info) {
                            return new AdditionalParameter($info->getName(), $info->getType(), true, null);
                        }, $method->getParameters())
                    );

                    //normalize the parameter list for the method
                    $parameters = $this->parameterNormalizer(array_merge($this->getParameters(), $this->getExtraParameters()), $method->getParameters());

                    $method_return = $this->execute($this->className, $method->getName(), $parameters);

                    /** We process the result returned by the method */
                    new ReturnResultHandler($method_return);

                    $this->clearStack();
                    break;
                }
            }
        }
    }

    /**
     * Adds the necessary parameters to the method that requires it
     * @param AdditionalParameter[] $parameters
     * @param MethodParamInfo[] $methodParametersSchema
     * @return array
     * @throws ClassNotFoundException
     */
    private function parameterNormalizer(array $parameters, array $methodParametersSchema): array
    {
        $methodParameters = [];
        foreach ($methodParametersSchema as $schema) {
            foreach ($parameters as $extraParameter) {
                if ($schema->getType() === $extraParameter->getType()) {
                    if ($extraParameter->isNeedCreateInstance() === true) {
                        $methodParameters[] = $this->initializeInstance($extraParameter->getType());
                    } else {
                        $methodParameters[] = $extraParameter->getInstance();
                    }

                }
            }
        }

        return $methodParameters;
    }


    /**
     * We give the opportunity to get data from the attribute if it passed validation
     * @param object[] $attributes
     */
    private function addExtraAttributeToParameters(array $attributes): void
    {
        foreach ($attributes as $attribute) {
            if ($attribute instanceof AttributeReturnInterface) {
                $this->addParameters(
                    new AdditionalParameter(
                        $attribute::class . self::FORWARDED_PARAMETER,
                        $attribute::class,
                        false,
                        $attribute
                    )
                );
            }
        }
    }

    /**
     * @return AdditionalParameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function addParameters(AdditionalParameter ...$instances): static
    {
        foreach ($instances as $instance) {
            isset($this->getParameters()[$instance->getType()]) ?:
                $this->parameters[$instance->getType()] = $instance;
        }

        return $this;
    }

    public function addExtraParameters(AdditionalParameter ...$instances): static
    {
        foreach ($instances as $instance) {
            isset($this->getParameters()[$instance->getType()]) ?:
                $this->extraParameters[$instance->getType()] = $instance;
        }

        return $this;
    }


    /**
     * @throws ClassNotFoundException
     */
    private function initializeInstance(string $class, ...$parameters): object
    {
        if (class_exists($class)) {
            return new $class(...$parameters);
        }

        throw new ClassNotFoundException("$class not found");
    }

    /**
     * We call methods from the class on which the correct route is set
     * And add arguments
     * method_exist is not needed since method 100% exists
     * @param string $className
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    private function execute(string $className, string $method, array $parameters): mixed
    {
        /**
         * @var object $userDefinedClass
         * @see Configuration::getAppNamespace()
         */
        $userDefinedClass = new $className;
        return $userDefinedClass->$method(...$parameters);
    }

    public function setCallableValidateAttribute(\Closure $closure): static
    {
        $this->callableValidateAttribute = $closure;
        return $this;
    }

    public function validateAttribute(object $attribute): bool
    {
        return (bool)($this->callableValidateAttribute)($attribute);
    }

    private function clearStack(): void
    {
        unset($this->parameters);
        $this->parameters = [];
    }

    /**
     * @return AdditionalParameter[]
     */
    public function getExtraParameters(): array
    {
        return $this->extraParameters;
    }

}