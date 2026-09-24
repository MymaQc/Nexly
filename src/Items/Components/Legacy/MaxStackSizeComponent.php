<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaxStackSizeComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::MAX_STACK_SIZE->getValue();
    }

    public function __construct(
        private readonly int $size
    ) {
        if ($size < 1 || $size > 64) {
            throw new InvalidArgumentException("Maximum stack size must be a positive NBT int.");
        }
    }

    /**
     * Get the maximum damage value.
     *
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->size);
    }
}
