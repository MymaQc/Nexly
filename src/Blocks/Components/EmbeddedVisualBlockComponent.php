<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class EmbeddedVisualBlockComponent extends BlockComponent
{
    /**
     * @param GeometryBlockComponent $geometry
     * @param MaterialInstancesBlockComponent $material
     */
    public function __construct(
        private readonly GeometryBlockComponent $geometry,
        private readonly MaterialInstancesBlockComponent $material
    ) {
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::EMBEDDED_VISUAL->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("geometryDescription", $this->geometry->toNBT())
            ->setTag("materialInstancesDescription", $this->material->toNBT());
    }
}
