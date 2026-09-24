<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Validation\ComponentValues;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

/**
 * Enables the Bedrock onEntityFallOn script event above the documented distance.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class EntityFallOnBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly float $minFallDistance = 0.0
    ) {
        ComponentValues::finite($minFallDistance);
        if ($minFallDistance < 0.0) {
            throw new InvalidArgumentException('Minimum fall distance cannot be negative.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::ENTITY_FALL_ON->value;
    }

    public function toNBT(): CompoundTag
    {
        // Bedrock Wiki and the Learn property table use min_fall_distance; Learn's sample uses minimum_fall_distance.
        return CompoundTag::create()->setTag('min_fall_distance', new FloatTag($this->minFallDistance));
    }
}
