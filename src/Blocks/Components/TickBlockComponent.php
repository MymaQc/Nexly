<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;

/**
 * Client-side block tick scheduling; tick events are consumed by Bedrock scripts.
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class TickBlockComponent extends BlockComponent
{
    /**
     * @param array{int,int} $intervalRangeTicks
     */
    public function __construct(
        private readonly array $intervalRangeTicks,
        private readonly bool $looping = true
    ) {
        if (!array_is_list($intervalRangeTicks) || \count($intervalRangeTicks) !== 2 || !\is_int($intervalRangeTicks[0]) || !\is_int($intervalRangeTicks[1]) || $intervalRangeTicks[0] < 0 || $intervalRangeTicks[0] > $intervalRangeTicks[1] || $intervalRangeTicks[1] > 2147483647) {
            throw new InvalidArgumentException('Tick interval must contain two ordered non-negative NBT integers.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::TICK->value;
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('interval_range', new ListTag([new IntTag($this->intervalRangeTicks[0]), new IntTag($this->intervalRangeTicks[1])], NBT::TAG_Int))
            ->setTag('looping', new ByteTag($this->looping ? 1 : 0));
    }
}
