<?php

namespace Nexly\Events\Attribute;

use Attribute;
use pocketmine\event\EventPriority as PMPriority;

#[Attribute(Attribute::TARGET_METHOD)]
class HandlePriority
{
    /**
     * @param int $priority
     */
    public function __construct(
        protected int $priority = PMPriority::NORMAL
    ) {
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }
}
