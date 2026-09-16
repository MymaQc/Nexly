<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\BlockSoundType;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * minecraft:sound - determines the sounds the block makes (mining, step, breaking and placement sounds).
 * Accepts a {@see BlockSoundType} for vanilla block sounds, or a namespaced string for a custom sound
 * definition (e.g. "wiki:chestnut_wood", supported since format version 1.26.30).
 * Released in format version 1.26.50.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SoundBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly BlockSoundType|string $sound,
    ) {
        if (is_string($this->sound) && $this->sound === "") {
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
            ->setTag("sound", new StringTag($this->sound instanceof BlockSoundType ? $this->sound->getValue() : $this->sound));
    }
}