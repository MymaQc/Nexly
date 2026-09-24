<?php

namespace Nexly\Blocks\Traits;

use InvalidArgumentException;
use Nexly\Validation\ComponentValues;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

readonly class MinecraftTrait
{
    /**
     * @param TraitIds $identifier
     * @param float $rotationOffset
     * @param State $state
     */
    public function __construct(
        private TraitIds $identifier,
        private float    $rotationOffset,
        private State    $state,
    ) {
        ComponentValues::finite($rotationOffset);
        if (fmod($rotationOffset, 90.0) !== 0.0) {
            throw new InvalidArgumentException("Trait rotation must be a multiple of 90 degrees.");
        }
        if ($identifier === TraitIds::PLACEMENT_POSITION && $rotationOffset !== 0.0) {
            throw new InvalidArgumentException("Placement-position traits do not support rotation offsets.");
        }
        if ($state->getEnabledStates() === []) {
            throw new InvalidArgumentException("A trait must enable at least one state.");
        }
        foreach ($state->getEnabledStates() as $enabled) {
            if ($enabled->getTrait() !== $identifier) {
                throw new InvalidArgumentException("State " . $enabled->value . " does not belong to " . $identifier->value);
            }
        }
    }

    /**
     * @return TraitIds
     */
    public function getIdentifier(): TraitIds
    {
        return $this->identifier;
    }
    /**
     * @return float
     */
    public function getRotationOffset(): float
    {
        return $this->rotationOffset;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("enabled_states", $this->state->toNBT())
            ->setTag("name", new StringTag($this->identifier->getValue()));
        if ($this->identifier === TraitIds::PLACEMENT_DIRECTION) {
            $nbt->setTag("y_rotation_offset", new FloatTag($this->rotationOffset));
        }
        return $nbt;
    }
}
