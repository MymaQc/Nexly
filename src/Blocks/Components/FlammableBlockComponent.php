<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\LavaFlammability;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FlammableBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly int $catchChanceModifier = 5,
        private readonly int $destroyChanceModifier = 20,
        private readonly LavaFlammability|bool $lavaFlammable = LavaFlammability::NEVER,
    ) {
        if ($catchChanceModifier < 0 || $catchChanceModifier > 2147483647 || $destroyChanceModifier < 0 || $destroyChanceModifier > 2147483647) {
            throw new InvalidArgumentException("Flammability modifiers must fit a non-negative NBT int.");
        }
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::FLAMMABLE->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("catch_chance_modifier", new IntTag($this->catchChanceModifier))
            ->setTag("destroy_chance_modifier", new IntTag($this->destroyChanceModifier))
            ->setTag("lava_flammable", new StringTag(
                $this->lavaFlammable instanceof LavaFlammability
                    ? $this->lavaFlammable->value
                    : ($this->lavaFlammable ? LavaFlammability::ALWAYS->value : LavaFlammability::NEVER->value)
            ));
    }
}
