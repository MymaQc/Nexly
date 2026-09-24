<?php

namespace Nexly\Items\Components\DataDriven\Types;

use Nexly\Validation\ComponentValues;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;

readonly class KineticEffectConditions
{
    public function __construct(
        private int   $maxDuration = -1,
        private float $minRelativeSpeed = 0.0,
        private float $minSpeed = 0.0,
    ) {
        ComponentValues::finite($minRelativeSpeed, $minSpeed);
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_duration", new IntTag($this->maxDuration))
            ->setTag("min_relative_speed", new FloatTag($this->minRelativeSpeed))
            ->setTag("min_speed", new FloatTag($this->minSpeed));
    }
}
