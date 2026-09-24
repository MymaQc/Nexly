<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class UseDurationComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::USE_DURATION->getValue();
    }

    public function __construct(
        private readonly int $value
    ) {
        if ($value < 0 || $value > 2147483647) {
            throw new InvalidArgumentException("Use duration must be a non-negative NBT int.");
        }
    }

    /**
     * Get the maximum damage value.
     *
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->value);
    }
}
