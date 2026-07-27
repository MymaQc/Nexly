<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CompostableItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly int $compostingChance,
    ) {
        if ($compostingChance < 1 || $compostingChance > 100) {
            throw new \InvalidArgumentException("Composting chance must be between 1 and 100.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::COMPOSTABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("composting_chance", new IntTag($this->compostingChance));
    }
}
