<?php
declare(strict_types=1);

namespace Astaroth\Generators;

use Astaroth\Attribute\ClassAttribute\Conversation;
use Astaroth\Attribute\ClassAttribute\Event;
use Astaroth\Attribute\General\Description;
use Astaroth\Attribute\Method\Message;
use Astaroth\Commands\BaseCommands;
use Astaroth\Contracts\GeneratorInterface;
use Astaroth\DataFetcher\Enums\Events;
use Astaroth\Enums\Events as EnumEvents;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;
use ReflectionClass;
use function array_flip;

final class EventGenerator implements GeneratorInterface
{

    /**
     * @throws NonExistentEventException
     */
    public static function generate(string $namespace, string $className, string $eventName): string
    {

        $file = new PhpFile;
        $file
            ->setStrictTypes(true);

        $ns = $file->addNamespace($namespace);
        $ns
            ->addUse(Event::class)
            ->addUse(EnumEvents::class)
            ->addUse(Message::class)
            ->addUse(Conversation::class)
            ->addUse(Description::class)
            ->addUse(BaseCommands::class);


        $class = $ns->addClass($className);

        $class
            ->setFinal(true)
            ->setExtends(BaseCommands::class)
            ->addAttribute(Event::class, [new Literal(self::parseEventPseudoEnum($eventName))])
            ->addAttribute(Conversation::class, [new Literal('Conversation::ALL')])
            ->addAttribute(Description::class, ["..."]);

        $class
            ->addMethod("method")
            ->addAttribute(Message::class, ["hi", new Literal('Message::CONTAINS')])
            ->setBody('$this->message("hi")->send();')
            ->setReturnType("void")
        ;

        return $file;
    }

    /**
     * @throws NonExistentEventException
     */
    private static function parseEventPseudoEnum(string $value): ?string
    {
        $r = new ReflectionClass(Events::class);

        $constants = $r->getConstants();
        $flipConst = array_flip($constants);

        $constant = $flipConst[$value] ?? throw new NonExistentEventException("$value event does not exist");

        return $r->getShortName() . '::' . $constant;
    }
}