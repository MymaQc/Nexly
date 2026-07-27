<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

readonly class FloatRange
{
    public function __construct(
        private float $min = 0.0,
        private float $max = 0.0,
    ) {
        if ($min > $max) {
            throw new \InvalidArgumentException("Range minimum cannot be greater than its maximum.");
        }
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("min", new FloatTag($this->min))
            ->setTag("max", new FloatTag($this->max));
    }
}
