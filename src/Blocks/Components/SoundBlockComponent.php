<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * minecraft:sound - determines the sounds the block makes (mining, step, breaking and placement sounds).
 * The value is the block sound name from RP/blocks.json (e.g. "glass", "wood", "stone").
 * Released in format version 1.26.50.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SoundBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly string $sound,
    ) {
        if ($sound === "") {
            throw new \InvalidArgumentException("Block sound type cannot be empty.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::SOUND->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("sound", new StringTag($this->sound));
    }
}