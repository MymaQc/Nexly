<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\BreathabilityType;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @internal Required only by the client block-definition NBT.
 * @deprecated Not usable as a public custom-content component.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class BreathabilityBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly BreathabilityType $type,
    ) {
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::BREATHABILITY->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new StringTag($this->type->getValue()));
    }
}
