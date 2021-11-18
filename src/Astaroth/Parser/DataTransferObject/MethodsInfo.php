<?php
declare(strict_types=1);

namespace Astaroth\Parser\DataTransferObject;


use Countable;
use Iterator;
use Stringable;

final class MethodsInfo implements Stringable, Iterator, Countable
{
    private int $position = 0;

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
                $attributesInfo .= $attr::class . ", ";
            }
            $attributesInfo = trim($attributesInfo, ", ");

            $methodsInfo .= "Method: {$method->getName()}\n";
            $methodsInfo .= "Enjoy attribute: $attributesInfo\n";
            $methodsInfo .= "Description: {$method->getDescription()}";
            $methodsInfo .= "\n\n";
        }

        return $methodsInfo;
    }

    public function current(): MethodInfo
    {
        return $this->methods[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->methods[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return \count($this->methods);
    }
}