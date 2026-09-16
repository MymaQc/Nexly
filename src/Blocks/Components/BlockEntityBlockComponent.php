<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;

/**
 * minecraft:block_entity - associates a block entity with the block, used to store additional data
 * beyond the block's permutations. Released in format version 1.26.50.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class BlockEntityBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool $dynamicProperties = false,
        private readonly ?int $containerSlotCount = null,
    ) {
        if ($containerSlotCount !== null && ($containerSlotCount < 1 || $containerSlotCount > 54)) {
            throw new \InvalidArgumentException("Container slot count must be between 1 and 54.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::BLOCK_ENTITY->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("dynamic_properties", new ByteTag($this->dynamicProperties ? 1 : 0));

        if ($this->containerSlotCount !== null) {
            $nbt->setTag("container", CompoundTag::create()
                ->setTag("slot_count", new IntTag($this->containerSlotCount)));
        }

        return $nbt;
    }
}