<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @see CustomComponentsBlockComponent
 * @deprecated
 */
#[Attribute(Attribute::TARGET_CLASS)]
class OnInteractBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly string $condition = "",
        private readonly string $event = "",
        private readonly string $target = "self",
    ) {
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::ON_INTERACT->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("condition", new StringTag($this->condition))
            ->setTag("event", new StringTag($this->event))
            ->setTag("target", new StringTag($this->target));
    }
}
