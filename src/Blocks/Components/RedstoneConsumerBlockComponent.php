<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

/**
 * Redstone update event configuration (format 1.21.130+; unavailable on permutations).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class RedstoneConsumerBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly int $minPower = 0,
        private readonly bool $propagatesPower = false
    ) {
        if ($minPower < 0 || $minPower > 15) {
            throw new InvalidArgumentException('Redstone minimum power must be from 0 to 15.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::REDSTONE_CONSUMER->value;
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('min_power', new IntTag($this->minPower))
            ->setTag('propagates_power', new ByteTag($this->propagatesPower ? 1 : 0));
    }
}
