<?php

declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Attribute\Action;
use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\ClientInfo;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\Payload;
use Astaroth\Attribute\State;
use Astaroth\Auth\Configuration;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Foundation\Utils;
use Astaroth\Parser\ClassNotFoundException;
use Astaroth\Parser\DataTransferObject\MethodParamInfo;
use Astaroth\Parser\DataTransferObject\MethodsInfo;
use Astaroth\Route\ReturnResultHandler;
use function in_array;

class MethodExecutor
{
    public const PROTECTED_INSTANCES =
        [
            MessageNew::class,
            MessageEvent::class,

            MessageRegex::class
        ];

    /** @var AdditionalParameter[] */
    private array $extraParameters = [];

    private array $availableAttribute = [];
    private null|MessageEvent|MessageNew $validateData = null;


    /**
     * General event coordinator
     * @param string $instanceName
     * @param MethodsInfo $methodsInfo DTO
     *
     * @see execute()
     */
    public function __construct(
        private string      $instanceName,
        private MethodsInfo $methodsInfo,
    )
    {
    }

    /**
     * We call methods from the class on which the correct route is set
     * And add arguments
     * method_exist is not needed since method 100% exists
     * @param string $instanceName
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    private function execute(string $instanceName, string $method, array $parameters): mixed
    {
        /**
         * @var object $userDefinedClass
         * @see Configuration::getAppNamespace()
         */
        $userDefinedClass = new $instanceName;
        return $userDefinedClass->$method(...$parameters);
    }

    public function launch(): void
    {
        foreach ($this->methodsInfo->getMethods() as $method) {
            foreach ($method->getAttribute() as $attribute) {
                if ($attribute instanceof AttributeValidatorInterface && $this->validateAttribute($attribute)) {
                    //if the validation is successful, proceed to the execution of the method

                    //passing attributes to parameters (if their type is explicitly specified in user-method)
                    if ($attribute instanceof MessageRegex) {
                        $this->addExtraParameters(
                            new AdditionalParameter(
                                self::getParameterName($attribute, $this->methodsInfo),
                                $attribute::class,
                                false,
                                $attribute
                            )
                        );


                    }

                    $this->addExtraParameters(...
                        array_map(static function (MethodParamInfo $info) {
                            return new AdditionalParameter($info->getName(), $info->getType(), true, null);
                        }, $method->getParameters())
                    );

                    //normalize the parameter list for the method
                    $parameters = $this->parameterNormalizer($method->getParameters());

                    $method_return = $this->execute($this->instanceName, $method->getName(), $parameters);

                    /** We process the result returned by the method */
                    new ReturnResultHandler($method_return);

                    break;
                }
            }
        }
    }

    /**
     * @param AttributeValidatorInterface $attribute
     * @return bool
     */
    private function validateAttribute(AttributeValidatorInterface $attribute): bool
    {
        if (in_array($attribute::class, $this->getAvailableAttribute(), true)) {

            if ($attribute::class === Message::class || $attribute::class === MessageRegex::class) {
                $attribute->setHaystack($this->getValidateData()?->getText());
            }
            if ($attribute::class === Payload::class) {
                $attribute->setHaystack($this->getValidateData()?->getPayload());
            }
            if ($attribute::class === Attachment::class) {
                $attribute->setHaystack($this->getValidateData()?->getAttachments());
            }
            if ($attribute::class === ClientInfo::class) {
                $attribute->setHaystack($this->getValidateData()?->getClientInfo());
            }
            if ($attribute::class === State::class) {
                $attribute->setHaystack($this->getValidateData());
            }
            if ($attribute::class === Action::class) {
                $attribute->setHaystack($this->getValidateData()?->getAction());
            }

            return $attribute->validate();
        }

        return false;
    }


    /**
     * Adds the necessary parameters to the method that requires it
     * @param MethodParamInfo[] $methodParametersSchema
     * @return array
     * @throws ClassNotFoundException
     */
    private function parameterNormalizer(array $methodParametersSchema): array
    {
        Utils::var_dumpToStdout($this->getExtraParameters());

        $methodParameters = [];
        foreach ($methodParametersSchema as $schema) {
            foreach ($this->getExtraParameters() as $extraParameter) {
                if ($schema->getType() === $extraParameter->getType() && $schema->getName() === $extraParameter->getName()) {

                    if ($extraParameter->isNeedCreateInstance() === true) {
                        $methodParameters[] = $this->initializeInstance($extraParameter->getType());
                    } else {
                        $methodParameters[] = $extraParameter->getInstance();
                    }

                }
            }
        }

        return $methodParameters;

//        foreach ($this->getExtraParameters() as $extraParameter) {
//
//        }

//        foreach ($methodParametersSchema as $parameter) {
//            if (
//                class_exists($parameter->getType())
//                && !in_array($parameter, $this->getExtraParameters(), true)
//            ) {
//                !in_array($parameter->getType(), self::PROTECTED_INSTANCES, true)
//                array_multisort($this->extraParameters, $schema);

//                TODO пробрасывать уже инициализированные инстансы
//                $this->addExtraParameters(new ($parameter->getType()));
//            }
//        }
    }


    /**
     * @return AdditionalParameter[]
     */
    public function getExtraParameters(): array
    {
        return $this->extraParameters;
    }

    /**
     * @param AdditionalParameter ...$instances
     * @return static
     */
    public function addExtraParameters(AdditionalParameter ...$instances): static
    {
        foreach ($instances as $instance) {
            $this->extraParameters[] = $instance;
        }

        return $this;
    }

    public function setAvailableAttribute(string ...$class): static
    {
        $this->availableAttribute = $class;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getAvailableAttribute(): array
    {
        return $this->availableAttribute;
    }

    public function setValidateData(MessageNew|MessageEvent $data): static
    {
        $this->validateData = $data;
        return $this;
    }

    /**
     * @return MessageEvent|MessageNew|null
     */
    public function getValidateData(): MessageNew|MessageEvent|null
    {
        return $this->validateData;
    }

    /**
     * @throws ClassNotFoundException
     */
    private function initializeInstance(string $class, ...$parameters)
    {
        if (class_exists($class)) {
            return new $class(...$parameters);
        }

        throw new ClassNotFoundException("$class not found");
    }

    /**
     * @param object $object
     * @param MethodsInfo $methods
     * @return string|null
     */
    public static function getParameterName(object $object, MethodsInfo $methods): ?string
    {
        foreach ($methods->getMethods() as $method) {
            foreach ($method->getParameters() as $parameter) {
                if ($parameter->getType() === $object::class) {
                    return $parameter->getName();
                }
            }
        }

        return null;
    }
}