<?php

namespace Nexly\Items\Components\DataDriven\Types;

use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

readonly class DurabilityThreshold
{
    public function __construct(
        private int     $durability,
        private SoundEvent|string|null $soundEvent = null,
        private ParticleType|string|null $particleType = null,
    ) {
        if ($durability < 0 || $durability > 2147483647) {
            throw new InvalidArgumentException("Durability threshold cannot be negative.");
        }
        // Since format 1.26.50, any sound_definitions.json name is accepted besides the built-in events
        if ($soundEvent === "") {
            throw new InvalidArgumentException("Sound event name cannot be empty.");
        }
        if (\is_string($particleType) && ParticleType::tryFrom($particleType) === null) {
            throw new InvalidArgumentException("Unknown built-in particle type; use ParticleType cases.");
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
        return $this->soundEvent instanceof SoundEvent ? $this->soundEvent->value : $this->soundEvent;
    }

    /**
     * @return string|null
     */
    public function getParticle(): ?string
    {
        return $this->particleType instanceof ParticleType ? $this->particleType->value : $this->particleType;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("durability", new IntTag($this->durability));

        if ($this->soundEvent !== null) {
            $nbt->setTag("sound_event", new StringTag($this->getSound()));
        }

        if ($this->particleType !== null) {
            $nbt->setTag("particle_type", new StringTag($this->getParticle()));
        }

        return $nbt;
    }
}
