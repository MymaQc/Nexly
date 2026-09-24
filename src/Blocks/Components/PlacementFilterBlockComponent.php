<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\PlacementCondition;
use Nexly\Validation\ComponentValues;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class PlacementFilterBlockComponent extends BlockComponent
{
    /**
     * @param list<PlacementCondition> $conditions
     */
    public function __construct(
        private readonly array $conditions,
    ) {
        ComponentValues::objects($conditions, PlacementCondition::class);
        if ($conditions === [] || \count($conditions) > 64) {
            throw new InvalidArgumentException('Placement filter requires between 1 and 64 conditions.');
        }
    }

    /**
     * The network component name for block placement conditions.
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
            ->setTag('conditions', new ListTag(array_map(static fn (PlacementCondition $condition): CompoundTag => $condition->toNBT(), $this->conditions)));
    }
}
