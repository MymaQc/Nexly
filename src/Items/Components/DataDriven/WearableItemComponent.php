<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemSlot;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class WearableItemComponent extends DataDrivenItemComponent
{
    /**
     * @param ItemSlot $slot
     * @param int $protection
     * @param bool $hidesPlayerLocation
     * @param bool|null $dispensable
     */
    public function __construct(
        private readonly ItemSlot $slot,
        private readonly int $protection = 0,
        private readonly bool $hidesPlayerLocation = false,
        private readonly ?bool $dispensable = null,
    ) {
        if ($protection < 0) {
            throw new \InvalidArgumentException("Wearable protection cannot be negative.");
        }

        if (!in_array($slot, [
            ItemSlot::ARMOR_BODY,
            ItemSlot::ARMOR_CHEST,
            ItemSlot::ARMOR_FEET,
            ItemSlot::ARMOR_HEAD,
            ItemSlot::ARMOR_LEGS,
            ItemSlot::WEAPON_MAIN_HAND,
            ItemSlot::WEAPON_OFF_HAND,
        ], true)) {
            throw new \InvalidArgumentException("Unsupported wearable equipment slot.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::WEARABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("slot", new StringTag($this->slot->getValue()))
            ->setTag("protection", new IntTag($this->protection))
            ->setTag("hides_player_location", new ByteTag($this->hidesPlayerLocation ? 1 : 0));

        if ($this->dispensable !== null) {
            $nbt->setTag("dispensable", new ByteTag($this->dispensable ? 1 : 0));
        }

        return $nbt;
    }
}
