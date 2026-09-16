<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\ChestObstructionRule;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * minecraft:chest_obstruction - determines when the opening of chests placed below the block should be
 * obstructed. Requires format version 1.26.20 or later.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class ChestObstructionBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly ChestObstructionRule $rule = ChestObstructionRule::SHAPE,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::CHEST_OBSTRUCTION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("obstruction_rule", new StringTag($this->rule->getValue()));
    }
}