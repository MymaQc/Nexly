<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructibleByExplosionBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly float $explosionResistance = 0.0,
    ) {
        if ($explosionResistance < 0.0) {
            throw new \InvalidArgumentException("Explosion resistance cannot be negative.");
        }
    }

    /**
     * Determines whether the block is destructible by explosions. The default is `0.0` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::DESTRUCTIBLE_BY_EXPLOSION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("explosion_resistance", new FloatTag($this->explosionResistance));
    }
}
