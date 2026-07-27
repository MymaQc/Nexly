<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

/**
 * @internal Legacy client hook metadata retained for network compatibility.
 * @deprecated minecraft:custom_components is no longer supported in current
 *             custom-content definitions.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class CustomComponentsBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool $hasPlayerInteract = true,
        private readonly bool $hasPlayerPlacing = true,
        private readonly bool $isV1Component = true,
    ) {
    }

    /**
     * Returns the name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::CUSTOM_COMPONENTS->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("hasPlayerInteract", new ByteTag($this->hasPlayerInteract ? 1 : 0))
            ->setTag("hasPlayerPlacing", new ByteTag($this->hasPlayerPlacing ? 1 : 0))
            ->setTag("isV1Component", new ByteTag($this->isV1Component ? 1 : 0));
    }
}
