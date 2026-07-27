<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\FloatRange;
use Nexly\Items\Components\DataDriven\Types\KineticEffectConditions;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_kinetic_weapon?view=minecraft-bedrock-stable
 * @since 1.21.120
 */
#[Attribute(Attribute::TARGET_CLASS)]
class KineticWeaponItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly float $hitboxMargin = 0.0,
        private readonly FloatRange $reach = new FloatRange(0.0, 3.0),
        private readonly ?FloatRange $creativeReach = null,
        private readonly float $damageModifier = 0.0,
        private readonly float $damageMultiplier = 1.0,
        private readonly int $delay = 0,
        private readonly ?KineticEffectConditions $damageConditions = null,
        private readonly ?KineticEffectConditions $dismountConditions = null,
        private readonly ?KineticEffectConditions $knockbackConditions = null,
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
        return DataDrivenComponentIds::KINETIC_WEAPON->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        if ($this->damageConditions !== null) {
            $nbt = $nbt->setTag("damage_conditions", $this->damageConditions->toNBT());
        }

        if ($this->dismountConditions !== null) {
            $nbt = $nbt->setTag("dismount_conditions", $this->dismountConditions->toNBT());
        }

        if ($this->knockbackConditions !== null) {
            $nbt = $nbt->setTag("knockback_conditions", $this->knockbackConditions->toNBT());
        }

        $nbt
            ->setTag("hitbox_margin", new FloatTag($this->hitboxMargin))
            ->setTag("reach", $this->reach->toNBT())
            ->setTag("damage_modifier", new FloatTag($this->damageModifier))
            ->setTag("damage_multiplier", new FloatTag($this->damageMultiplier))
            ->setTag("delay", new IntTag($this->delay));

        if ($this->creativeReach !== null) {
            $nbt->setTag("creative_reach", $this->creativeReach->toNBT());
        }

        return $nbt;
    }
}
