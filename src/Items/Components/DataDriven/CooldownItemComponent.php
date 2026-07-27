<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemCooldownAction;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class CooldownItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $duration,
        private readonly string $category = "",
        private readonly ItemCooldownAction $type = ItemCooldownAction::USE,
    ) {
        if ($duration < 0.0) {
            throw new \InvalidArgumentException("Cooldown duration cannot be negative.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::COOLDOWN->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("duration", new FloatTag($this->duration))
            ->setTag("category", new StringTag($this->category))
            ->setTag("type", new StringTag($this->type->getValue()));
    }
}
