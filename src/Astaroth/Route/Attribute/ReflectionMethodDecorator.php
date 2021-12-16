<?php
declare(strict_types=1);

namespace Astaroth\Route\Attribute;

use Astaroth\Contracts\AttributeReturnInterface;
use ReflectionAttribute;
use ReflectionMethod;

final class ReflectionMethodDecorator extends ReflectionMethod
{
    /** @var array<string, AttributeReturnInterface> */
    private array $replacedAttributes = [];

    public function addReplaceAttribute(AttributeReturnInterface $attribute): ReflectionMethodDecorator
    {
        $this->replacedAttributes[$attribute::class] = $attribute;
        return $this;
    }

    /**
     * Replace attributes with already created, validated instances
     * @param string|null $name
     * @param int $flags
     * @return AttributeReturnInterface[]|ReflectionAttribute[]
     *
     * @psalm-suppress ImplementedReturnTypeMismatch, ArgumentTypeCoercion
     * @noinspection PhpDocSignatureInspection
     */
    public function getAttributes(?string $name = null, int $flags = 0): array
    {
        $attr = [];
        foreach ($this->replacedAttributes as $replacedAttribute) {
            $attr[$replacedAttribute::class] = $replacedAttribute;
        }

        foreach (parent::getAttributes($name, $flags) as $attribute) {
            if (isset($attr[$attribute->getName()]) === false) {
                $attr[$attribute->getName()] = $attribute;
            }
        }

        return $attr;
    }
}