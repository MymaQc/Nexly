<?php

namespace Nexly\Blocks\Permutations;

use InvalidArgumentException;
use Nexly\Blocks\Components\AsyncBlockComponent;
use Nexly\Blocks\Components\BlockComponent;
use Nexly\Blocks\Components\GeometryBlockComponent;
use pocketmine\nbt\tag\CompoundTag;

final class Permutation
{
    /**
     * @var array<string, BlockComponent>
     */
    private array $components = [];

    public function __construct(
        private string $condition
    ) {
    }

    /**
     * @param string $condition
     * @return self
     */
    public static function create(string $condition = ""): self
    {
        return new self($condition);
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @param string $condition
     * @return Permutation
     */
    public function setCondition(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return array<string, BlockComponent>
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @param BlockComponent $component
     * @return $this
     */
    public function addComponent(BlockComponent $component): self
    {
        if (\in_array($component->getName(), ["minecraft:flower_pottable", "minecraft:embedded_visual", "minecraft:redstone_consumer"], true)) {
            throw new InvalidArgumentException($component->getName() . " is only supported in root block components.");
        }
        if ($component instanceof GeometryBlockComponent && $component->hasNWayVisualRotation()) {
            throw new InvalidArgumentException("minecraft:geometry.n_way_visual_rotation is only supported in root block components.");
        }
        if ($component instanceof AsyncBlockComponent && $component->getName() === "minecraft:geometry" && $component->toNBT() instanceof CompoundTag && $component->toNBT()->getTag("n_way_visual_rotation") !== null) {
            throw new InvalidArgumentException("minecraft:geometry.n_way_visual_rotation is only supported in root block components.");
        }
        $this->components[$component->getName()] = $component;
        return $this;
    }

    /**
     * Returns the permutation in the correct NBT format supported by the client.
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $components = CompoundTag::create();
        foreach ($this->components as $name => $component) {
            $components->setTag($name, $component->toNBT());
        }

        return CompoundTag::create()
            ->setString("condition", $this->condition)
            ->setTag("components", $components);
    }
}
