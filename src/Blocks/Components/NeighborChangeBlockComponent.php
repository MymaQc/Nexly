<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\BlockFace;
use Nexly\Validation\ComponentValues;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

/**
 * minecraft:neighbor_change - chooses which neighbouring permutation changes trigger the onNeighborChanged()
 * custom component event. Without this component, changes from every direction trigger it.
 * Requires format version 1.26.60 and the Upcoming Creator Features experiment.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class NeighborChangeBlockComponent extends BlockComponent
{
    /**
     * @param list<BlockFace> $directions Empty list means "all"
     */
    public function __construct(
        private readonly array $directions = [],
    ) {
        ComponentValues::objects($directions, BlockFace::class);
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::NEIGHBOR_CHANGE->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $directions = $this->directions === []
            ? ["all"]
            : array_values(array_unique(array_map(static fn (BlockFace $face): string => $face->getValue(), $this->directions)));

        return CompoundTag::create()
            ->setTag("neighbor_directions", new ListTag(array_map(static fn (string $direction): StringTag => new StringTag($direction), $directions), NBT::TAG_String));
    }
}
