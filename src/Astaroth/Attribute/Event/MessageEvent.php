<?php
declare(strict_types=1);


namespace Astaroth\Attribute\Event;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
/**
 * Attribute defining new message
 */
class MessageEvent{}