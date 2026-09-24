<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

/** Base redstone conductivity (format 1.21.40+). */
#[Attribute(Attribute::TARGET_CLASS)]
final class RedstoneConductivityBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool $allowsWireToStepDown = true,
        private readonly bool $redstoneConductor = false
    ) {
    }

    public function getName(): string
    {
        return BlockComponentIds::REDSTONE_CONDUCTIVITY->value;
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag('allows_wire_to_step_down', new ByteTag($this->allowsWireToStepDown ? 1 : 0))
            ->setTag('redstone_conductor', new ByteTag($this->redstoneConductor ? 1 : 0));
    }
}
