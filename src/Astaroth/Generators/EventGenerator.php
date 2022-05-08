<?php
declare(strict_types=1);

namespace Astaroth\Generators;

use Astaroth\Attribute\ClassAttribute\Conversation;
use Astaroth\Attribute\ClassAttribute\Event;
use Astaroth\Attribute\General\Description;
use Astaroth\Attribute\Method\Message;
use Astaroth\Commands\BaseCommands;
use Astaroth\Contracts\GeneratorInterface;
use Astaroth\Enums\ConversationType;
use Astaroth\Enums\Events as EnumEvents;
use Astaroth\Enums\MessageValidation;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;
use Throwable;

final class EventGenerator implements GeneratorInterface
{

    /**
     * @throws NonExistentEventException
     * @throws Throwable
     */
    public static function generate(string $namespace, string $className, string $eventName): string
    {
        $file = new PhpFile();
        $file
            ->setStrictTypes();

        $ns = $file->addNamespace($namespace);
        $ns
            ->addUse(Event::class)
            ->addUse(EnumEvents::class, 'Events')
            ->addUse(Message::class)
            ->addUse(MessageValidation::class)
            ->addUse(Conversation::class)
            ->addUse(ConversationType::class)
            ->addUse(Description::class)
            ->addUse(BaseCommands::class);


        $class = $ns->addClass($className);

        $class
            ->setFinal()
            ->setExtends(BaseCommands::class)
            ->addAttribute(Event::class, [self::parseEventEnum($eventName)])
            ->addAttribute(Conversation::class, [self::generateConversationEnum()])
            ->addAttribute(Description::class, ["..."]);

        $class
            ->addMethod("method")
            ->addAttribute(Message::class, ["hi", self::generateMessageValidationEnum()])
            ->setBody('$this->message("hi")->send();')
            ->setReturnType("void");

        return $file->__toString();
    }

    /**
     * @throws NonExistentEventException
     */
    private static function parseEventEnum(string $event): Literal
    {

        foreach (EnumEvents::cases() as $case) {
            if ($case->value === $event) {
                return new Literal('Events::' . $case->name);
            }
        }

        throw new NonExistentEventException("$event event does not exist");
    }

    private static function generateConversationEnum(): Literal
    {
        return new Literal('ConversationType::' . ConversationType::ALL->name);
    }

    private static function generateMessageValidationEnum(): Literal
    {
        return new Literal('MessageValidation::CONTAINS');
    }
}