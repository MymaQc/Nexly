<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

/**
 * minecraft:vibration_properties - whether the block dampens vibrations caused by contact with it,
 * and whether it occludes vibrations passing through it.
 * Requires format version 1.26.60 and the Upcoming Creator Features experiment.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class VibrationPropertiesBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool $dampenVibrations = false,
        private readonly bool $occludeVibrations = false,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::VIBRATION_PROPERTIES->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("dampen_vibrations", new ByteTag($this->dampenVibrations ? 1 : 0))
            ->setTag("occlude_vibrations", new ByteTag($this->occludeVibrations ? 1 : 0));
    }
}
