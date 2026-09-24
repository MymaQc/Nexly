<?php

namespace Nexly\Blocks\Traits;

use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

readonly class State
{
    public function __construct(
        private bool $cardinal = false,
        private bool $facing = false,
        private bool $blockFace = false,
        private bool $verticalHalf = false,
        private bool $sixteenWayRotation = false,
        private bool $cornerAndCardinalDirection = false,
    ) {
    }

    public static function enabled(TraitState ...$states): self
    {
        return new self(
            \in_array(TraitState::CARDINAL_DIRECTION, $states, true),
            \in_array(TraitState::FACING_DIRECTION, $states, true),
            \in_array(TraitState::BLOCK_FACE, $states, true),
            \in_array(TraitState::VERTICAL_HALF, $states, true),
            \in_array(TraitState::SIXTEEN_WAY_ROTATION, $states, true),
            \in_array(TraitState::CORNER_AND_CARDINAL_DIRECTION, $states, true),
        );
    }

    /**
     * @return array<string, bool> Worker-safe representation, retaining old keys.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromArray(array $values): self
    {
        $defaults = (new self())->toArray();
        foreach ($values as $name => $value) {
            if (!\array_key_exists($name, $defaults) || !\is_bool($value)) {
                throw new InvalidArgumentException("Unknown trait state or non-boolean state flag.");
            }
        }
        return new self(...array_replace($defaults, $values));
    }

    /**
     * @return list<TraitState>
     */
    public function getEnabledStates(): array
    {
        return array_values(array_filter(TraitState::cases(), fn (TraitState $state): bool => match ($state) {
            TraitState::CARDINAL_DIRECTION => $this->cardinal,
            TraitState::FACING_DIRECTION => $this->facing,
            TraitState::BLOCK_FACE => $this->blockFace,
            TraitState::VERTICAL_HALF => $this->verticalHalf,
            TraitState::SIXTEEN_WAY_ROTATION => $this->sixteenWayRotation,
            TraitState::CORNER_AND_CARDINAL_DIRECTION => $this->cornerAndCardinalDirection,
        }));
    }

    /**
     * @return list<string> Actual states supplied by the selected trait flags.
     */
    public function getProvidedStateNames(): array
    {
        $names = [];
        foreach ($this->getEnabledStates() as $state) {
            if ($state === TraitState::CORNER_AND_CARDINAL_DIRECTION) {
                $names[] = 'minecraft:corner';
                $names[] = 'minecraft:cardinal_direction';
            } else {
                $names[] = 'minecraft:' . $state->value;
            }
        }
        return array_values(array_unique($names));
    }

    /**
     * @return bool
     */
    public function isCardinal(): bool
    {
        return $this->cardinal;
    }

    /**
     * @return bool
     */
    public function isFacing(): bool
    {
        return $this->facing;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("cardinal_direction", new ByteTag($this->cardinal ? 1 : 0))
            ->setTag("facing_direction", new ByteTag($this->facing ? 1 : 0));
        foreach ($this->getEnabledStates() as $state) {
            $nbt->setByte($state->value, 1);
        }
        return $nbt;
    }
}
