<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\NoteInstrument;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * minecraft:instrument_sound - determines the sound played when a Note Block is placed above or below
 * the block. Released in format version 1.26.40.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class InstrumentSoundBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly ?NoteInstrument $up = null,
        private readonly ?NoteInstrument $down = null,
    ) {
        if ($up === null && $down === null) {
            throw new \InvalidArgumentException("At least one instrument sound face ('up' or 'down') must be defined.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::INSTRUMENT_SOUND->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create();
        if ($this->up !== null) {
            $nbt->setTag("up", new StringTag($this->up->getValue()));
        }
        if ($this->down !== null) {
            $nbt->setTag("down", new StringTag($this->down->getValue()));
        }

        return $nbt;
    }
}