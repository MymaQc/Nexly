<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class DurabilityThreshold
{
    public function __construct(
        private readonly int $durability,
        private readonly ?string $soundEvent = null,
        private readonly ?string $particleType = null,
    ) {
        if ($durability < 0) {
            throw new \InvalidArgumentException("Durability threshold cannot be negative.");
        }
    }

    /**
     * @return int
     */
    public function getDurability(): int
    {
        return $this->durability;
    }

    /**
     * @return string|null
     */
    public function getSound(): ?string
    {
        return $this->soundEvent;
    }

    /**
     * @return string|null
     */
    public function getParticle(): ?string
    {
        return $this->particleType;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("durability", new IntTag($this->durability));

        if ($this->soundEvent !== null) {
            $nbt->setTag("sound_event", new StringTag($this->soundEvent));
        }

        if ($this->particleType !== null) {
            $nbt->setTag("particle_type", new StringTag($this->particleType));
        }

        return $nbt;
    }
}
