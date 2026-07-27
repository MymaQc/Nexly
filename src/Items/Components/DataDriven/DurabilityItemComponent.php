<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DurabilityItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly int $maxDurability,
        private readonly int $damageChanceMin = 100,
        private readonly int $damageChanceMax = 100,
    ) {
        if ($maxDurability < 0) {
            throw new \InvalidArgumentException("Maximum durability cannot be negative.");
        }

        if ($damageChanceMin < 0 || $damageChanceMax < 0) {
            throw new \InvalidArgumentException("Min and Max damage chance must be at least 0");
        }

        if ($damageChanceMin > $damageChanceMax) {
            throw new \InvalidArgumentException("Min damage chance cannot be greater than Max damage chance");
        }

        if ($damageChanceMax > 100) {
            throw new \InvalidArgumentException("Max damage chance cannot be greater than 100");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DURABILITY->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_durability", new IntTag($this->maxDurability))
            ->setTag("damage_chance", CompoundTag::create()
                ->setTag("min", new IntTag($this->damageChanceMin))
                ->setTag("max", new IntTag($this->damageChanceMax))
            );
    }
}
