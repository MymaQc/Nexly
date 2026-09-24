<?php

namespace Nexly\Items\Components\Legacy\Types;

use InvalidArgumentException;
use Nexly\Validation\ComponentValues;

use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\lang\Translatable;
use pocketmine\nbt\tag\CompoundTag;

readonly class LegacyEffect
{
    /**
     * LegacyEffect constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $descriptionId
     * @param int $duration
     * @param int $amplifier
     * @param float $chance
     */
    public function __construct(
        private int    $id,
        private string $name,
        private string $descriptionId,
        private int    $duration = -1,
        private int    $amplifier = 0,
        private float  $chance = 100.0
    ) {
        ComponentValues::finite($chance);
        if ($id < 0 || $id > 2147483647 || $duration < -1 || $amplifier < 0 || $chance < 0.0 || $chance > 100.0) {
            throw new InvalidArgumentException("Legacy effect id/amplifier/duration/chance is outside its supported range.");
        }
    }

    /**
     * Create a LegacyEffect from an EffectInstance.
     *
     * @param EffectInstance $effectInstance
     * @return self
     */
    public static function from(EffectInstance $effectInstance): self
    {
        $name = $effectInstance->getType()->getName();

        return new self(
            EffectIdMap::getInstance()->toId($effectInstance->getType()),
            $name instanceof Translatable ? $name->getText() : $name,
            "",
            $effectInstance->getDuration(),
            $effectInstance->getAmplifier(),
            100.0
        );
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescriptionId(): string
    {
        return $this->descriptionId;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getAmplifier(): int
    {
        return $this->amplifier;
    }

    /**
     * @return float
     */
    public function getChance(): float
    {
        return $this->chance;
    }

    /**
     * Convert the effect to a CompoundTag for NBT representation.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setInt("id", $this->getId())
            ->setString("name", $this->getName())
            ->setString("description_id", $this->getDescriptionId())
            ->setInt("duration", $this->getDuration())
            ->setInt("amplifier", $this->getAmplifier())
            ->setFloat("chance", $this->getChance());
    }
}
