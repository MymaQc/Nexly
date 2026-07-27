<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

readonly class ItemSpecificSpeed
{
    public function __construct(
        private string|ItemDescriptor $item,
        private float $destroySpeed,
    ) {
        if ($item === "") {
            throw new \InvalidArgumentException("Item identifier cannot be empty.");
        }

        if ($destroySpeed < 0.0) {
            throw new \InvalidArgumentException("Item-specific destroy speed cannot be negative.");
        }
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("item", is_string($this->item) ? new StringTag($this->item) : $this->item->toNBT())
            ->setTag("destroy_speed", new FloatTag($this->destroySpeed));
    }
}
