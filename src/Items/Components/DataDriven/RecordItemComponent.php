<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RecordItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $soundEvent
     * @param float $duration
     * @param int $comparatorSignal
     */
    public function __construct(
        private readonly string $soundEvent,
        private readonly float $duration = 0.0,
        private readonly int $comparatorSignal = 1
    ) {
        if ($duration < 0.0) {
            throw new \InvalidArgumentException("Record duration cannot be negative.");
        }

        if ($comparatorSignal < 1 || $comparatorSignal > 13) {
            throw new \InvalidArgumentException("Comparator signal must be between 1 and 13.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::RECORD->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("sound_event", new StringTag($this->soundEvent))
            ->setTag("duration", new FloatTag($this->duration))
            ->setTag("comparator_signal", new IntTag($this->comparatorSignal));
    }
}
