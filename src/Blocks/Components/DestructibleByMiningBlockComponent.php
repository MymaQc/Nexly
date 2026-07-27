<?php

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Blocks\Components\Types\ItemSpecificSpeed;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DestructibleByMiningBlockComponent extends BlockComponent
{
    /**
     * @param list<ItemSpecificSpeed> $itemSpecificSpeeds
     */
    public function __construct(
        private readonly float $secondsToDestroy = 0.0,
        private readonly array $itemSpecificSpeeds = [],
    ) {
        if ($secondsToDestroy < 0.0) {
            throw new \InvalidArgumentException("Seconds to destroy cannot be negative.");
        }
    }

    /**
     * Determines whether the block is breathable by defining if the block is treated as a `solid` or as `air`. The default is `solid` if this component is omitted
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::DESTRUCTIBLE_BY_MINING->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new FloatTag($this->secondsToDestroy))
            ->setTag("seconds_to_destroy", new FloatTag($this->secondsToDestroy))
            ->setTag("item_specific_speeds", new ListTag(array_map(fn (ItemSpecificSpeed $speed): CompoundTag => $speed->toNBT(), $this->itemSpecificSpeeds), NBT::TAG_Compound));
    }
}
