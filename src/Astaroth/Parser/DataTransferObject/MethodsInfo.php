<?php
declare(strict_types=1);

namespace Astaroth\Parser\DataTransferObject;


use Stringable;

final class MethodsInfo implements Stringable
{
    /**
     * @param MethodInfo[] $methods
     */
    public function __construct
    (
        private array $methods,
    )
    {
    }

    /**
     * @return MethodInfo[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    public function __toString()
    {
        $methodsInfo = "";
        foreach ($this->getMethods() as $method) {
            $attributesInfo = "";
            foreach ($method->getAttribute() as $attr) {
                $attributesInfo .= $attr::class;
            }

            $methodsInfo .= "Method: {$method->getName()}\n";
            $methodsInfo .= "Enjoy attribute: $attributesInfo\n";
            $methodsInfo .= "Description: {$method->getDescription()}";
            $methodsInfo .= "\n\n";
        }

        return $methodsInfo;
    }
}