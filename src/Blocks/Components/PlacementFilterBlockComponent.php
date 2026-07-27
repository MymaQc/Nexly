<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class PlacementFilterBlockComponent extends BlockComponent
{
    /**
     * @param list<IntTag> $allowedFaces
     * @param list<StringTag> $blockFilter
     */
    public function __construct(
        private readonly array $allowedFaces = [],
        private readonly array $blockFilter = [],
    ) {
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::PLACEMENT_FILTER->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("conditions", CompoundTag::create()
            ->setTag("allowed_faces", new ListTag($this->allowedFaces, NBT::TAG_Int)))
            ->setTag("block_filter", new ListTag($this->blockFilter, NBT::TAG_String));
    }
}
