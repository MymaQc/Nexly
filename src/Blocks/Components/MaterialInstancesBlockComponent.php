<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\Material;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class MaterialInstancesBlockComponent extends BlockComponent
{
    /**
     * @param Material[] $materials
     */
    public function __construct(
        private readonly array $materials,
        private ?CompoundTag $mappings = null
    ) {
        $this->mappings ??= CompoundTag::create();
        $this->validate();
    }

    private function validate(): void
    {
        $names = [];
        $materialNames = [];
        $renderMethod = null;
        foreach ($this->materials as $material) {
            if (!$material instanceof Material) {
                throw new InvalidArgumentException("Material instances must be Material objects.");
            }
            $name = $material->getTargetName();
            if (isset($names[$name])) {
                throw new InvalidArgumentException("Material instance names must be unique.");
            }
            $names[$name] = true;
            $materialNames[$name] = true;
            if ($renderMethod !== null && $renderMethod !== $material->getRenderMethod()) {
                throw new InvalidArgumentException("All material instances must use the same render method.");
            }
            $renderMethod = $material->getRenderMethod();
        }
        foreach ($this->mappings as $name => $mapping) {
            if ($name === "" || !$mapping instanceof \pocketmine\nbt\tag\StringTag || $mapping->getValue() === "") {
                throw new InvalidArgumentException("Material mappings require named non-empty string targets.");
            }
            if (!isset($materialNames[$mapping->getValue()])) {
                throw new InvalidArgumentException("Material mappings must reference a declared material instance.");
            }
            $names[$name] = true;
        }
        if (\count($names) > 64) {
            throw new InvalidArgumentException("At most 64 material instances and mappings are supported.");
        }
    }

    /**
     * The network component name for block material instances.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::MATERIAL_INSTANCES->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $this->validate();
        $materials = CompoundTag::create();
        foreach ($this->materials as $material) {
            $materials->setTag($material->getTargetName(), $material->toNBT());
        }

        return CompoundTag::create()
            ->setTag("mappings", $this->mappings)
            ->setTag("materials", $materials);
    }
}
