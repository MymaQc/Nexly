<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\DurabilityThreshold;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DurabilitySensorItemComponent extends DataDrivenItemComponent
{
    /**
     * @param list<DurabilityThreshold> $durabilityThresholds
     */
    public function __construct(
        private readonly array $durabilityThresholds,
    ) {
        if (empty($this->durabilityThresholds)) {
            throw new \InvalidArgumentException("Thresholds array cannot be empty.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DURABILITY_SENSOR->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("durability_thresholds", new ListTag(array_map(fn (DurabilityThreshold $threshold): CompoundTag => $threshold->toNBT(), $this->durabilityThresholds), NBT::TAG_Compound));
    }
}
