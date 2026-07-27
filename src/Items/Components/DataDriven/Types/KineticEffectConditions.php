<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;

class KineticEffectConditions
{
    public function __construct(
        private readonly int $maxDuration = -1,
        private readonly float $minRelativeSpeed = 0.0,
        private readonly float $minSpeed = 0.0,
    ) {
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_duration", new IntTag($this->maxDuration))
            ->setTag("min_relative_speed", new FloatTag($this->minRelativeSpeed))
            ->setTag("min_speed", new FloatTag($this->minSpeed));
    }
}
