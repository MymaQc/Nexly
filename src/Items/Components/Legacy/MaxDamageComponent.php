<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaxDamageComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::MAX_DAMAGE->getValue();
    }

    public function __construct(
        private readonly int $maxDamage
    ) {
        if ($maxDamage < 0 || $maxDamage > 2147483647) {
            throw new InvalidArgumentException("Maximum damage must be a non-negative NBT int.");
        }
    }

    /**
     * Get the maximum damage value.
     *
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->maxDamage);
    }
}
