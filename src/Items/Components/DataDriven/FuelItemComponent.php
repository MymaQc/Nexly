<?php

namespace Nexly\Items\Components\DataDriven;

use Nexly\Validation\ComponentValues;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FuelItemComponent extends DataDrivenItemComponent
{
    /**
     * @param float $duration
     */
    public function __construct(
        private readonly float $duration
    ) {
        ComponentValues::finite($duration);
        if ($this->duration < 0.05) {
            throw new InvalidArgumentException("Duration must be at least 0.05");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::FUEL->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("duration", new FloatTag($this->duration));
    }
}
