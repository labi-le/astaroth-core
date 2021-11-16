<?php
declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Attribute\Action;
use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\ClientInfo;
use Astaroth\Attribute\Debug;
use Astaroth\Attribute\Message;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\Payload;
use Astaroth\Attribute\State;
use Astaroth\Contracts\AttributeValidatorInterface;
use Astaroth\DataFetcher\Events\MessageEvent;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Parser\DataTransferObject\ClassInfo;

class EventDispatcher
{
    public function __construct(
        ClassInfo                       $classInfo,
        private MessageEvent|MessageNew $validateData
    )
    {
        (new MethodExecutor($classInfo->getName(), $classInfo->getMethods()))
            ->setCallableValidateAttribute($this->getValidateAttributeClosure())
            ->addParameters(new AdditionalParameter(
                $this->validateData::class,
                $this->validateData::class,
                false,
                $this->validateData
            ))
            ->launch();
    }


    /**
     * @return \Closure
     */
    private function getValidateAttributeClosure(): \Closure
    {
        return function ($attribute) {
            if ($attribute instanceof AttributeValidatorInterface) {
                return (match ($this->validateData::class) {
                    MessageNew::class => $this->messageNewValidate($attribute),
                    MessageEvent::class => $this->messageEventValidate($attribute),

                    //WallPostNew::class
                    //todo implement another events
                    default => $this->defaultValidate($attribute)
                })->validate();

            }

            return false;
        };
    }

    private function messageNewValidate(AttributeValidatorInterface $attribute): AttributeValidatorInterface
    {
        if ($attribute::class === Message::class || $attribute::class === MessageRegex::class) {
            $attribute->setHaystack($this->validateData->getText());
        }

        if (($attribute::class === Attachment::class)) {
            $attribute->setHaystack($this->validateData->getAttachments());
        }

        if (($attribute::class === Action::class)) {
            $attribute->setHaystack($this->validateData->getAction());
        }

        return $attribute;
    }

    private function messageEventValidate(AttributeValidatorInterface $attribute): AttributeValidatorInterface
    {
        if ($attribute::class === Payload::class) {
            $attribute->setHaystack($this->validateData->getPayload());
        }

        return $attribute;
    }

    private function defaultValidate(AttributeValidatorInterface $attribute): AttributeValidatorInterface
    {
        if ($attribute instanceof Debug || $attribute instanceof State) {
            $attribute->setHaystack($this->validateData);
        }
        if ($attribute::class === ClientInfo::class) {
            $attribute->setHaystack($this->validateData->getClientInfo());
        }

        return $attribute;
    }

}