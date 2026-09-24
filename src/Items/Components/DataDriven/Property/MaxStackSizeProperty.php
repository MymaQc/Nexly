<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaxStackSizeProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly int $size
    ) {
        if ($size < 1 || $size > 2147483647) {
            throw new InvalidArgumentException("Maximum stack size must be a positive NBT int.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::MAX_STACK_SIZE->getValue();
    }

    /**
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->size);
    }
}
