<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DisplayNameBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::DISPLAY_NAME->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new StringTag($this->name));
    }
}
