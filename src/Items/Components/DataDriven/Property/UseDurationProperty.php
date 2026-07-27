<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use pocketmine\nbt\tag\IntTag;

/**
 * @deprecated Use UseModifiersItemComponent. The minecraft:use_duration
 *             component is no longer supported by current Bedrock versions.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class UseDurationProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly int $tick
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::USE_DURATION->getValue();
    }

    /**
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->tick);
    }
}
