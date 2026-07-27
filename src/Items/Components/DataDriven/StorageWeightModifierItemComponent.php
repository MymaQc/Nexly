<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageWeightModifierItemComponent extends DataDrivenItemComponent
{
    /**
     * @param int $weightInStorageItem
     */
    public function __construct(
        private readonly int $weightInStorageItem = 4,
    ) {
        if ($weightInStorageItem < 0) {
            throw new \InvalidArgumentException("Storage weight cannot be negative.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::STORAGE_WEIGHT_MODIFIER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("weight_in_storage_item", new IntTag($this->weightInStorageItem));
    }
}
