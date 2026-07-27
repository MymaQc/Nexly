<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ProjectileItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $projectileEntity
     * @param float $minimumCriticalPower
     */
    public function __construct(
        private readonly string $projectileEntity,
        private readonly float $minimumCriticalPower = 0.0,
    ) {
        if ($projectileEntity === "") {
            throw new \InvalidArgumentException("Projectile entity identifier cannot be empty.");
        }

        if ($minimumCriticalPower < 0.0) {
            throw new \InvalidArgumentException("Minimum critical power cannot be negative.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::PROJECTILE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("projectile_entity", new StringTag($this->projectileEntity))
            ->setTag("minimum_critical_power", new FloatTag($this->minimumCriticalPower));
    }
}
