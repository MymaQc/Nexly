<?php

namespace Nexly\Blocks\Components;

use Nexly\Validation\ComponentValues;

use Attribute;
use InvalidArgumentException;
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
        ComponentValues::objects($itemSpecificSpeeds, ItemSpecificSpeed::class);
        ComponentValues::finite($secondsToDestroy);
        if ($secondsToDestroy < 0.0) {
            throw new InvalidArgumentException("Seconds to destroy cannot be negative.");
        }
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::DESTRUCTIBLE_BY_MINING->getValue();
    }

    /**
     * Item-specific speed rules require Bedrock's Upcoming Creator Features experiment.
     * Returns the component in the NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("seconds_to_destroy", new FloatTag($this->secondsToDestroy))
            ->setTag("item_specific_speeds", new ListTag(array_map(static fn (ItemSpecificSpeed $speed): CompoundTag => $speed->toNBT(), $this->itemSpecificSpeeds), NBT::TAG_Compound));
    }
}
