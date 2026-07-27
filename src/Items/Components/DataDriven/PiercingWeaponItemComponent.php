<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\FloatRange;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_piercing_weapon?view=minecraft-bedrock-stable
 * @since 1.21.120
 */
#[Attribute(Attribute::TARGET_CLASS)]
class PiercingWeaponItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $hitboxMargin = 0.0,
        private readonly FloatRange $reach = new FloatRange(0.0, 3.0),
        private readonly ?FloatRange $creativeReach = null,
    ) {
        if ($hitboxMargin < 0.0) {
            throw new \InvalidArgumentException("Hitbox margin cannot be negative.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::PIERCING_WEAPON->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("hitbox_margin", new FloatTag($this->hitboxMargin))
            ->setTag("reach", $this->reach->toNBT());

        if ($this->creativeReach !== null) {
            $nbt->setTag("creative_reach", $this->creativeReach->toNBT());
        }

        return $nbt;
    }
}
