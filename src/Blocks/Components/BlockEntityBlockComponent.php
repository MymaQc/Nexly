<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Compatibility\ComponentFormatFeatureProvider;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/**
 * minecraft:block_entity - associates a block entity with the block, used to store additional data
 * beyond the block's permutations. Released in format version 1.26.50.
 * The container title and entity storage fields need format version 1.26.60 and Upcoming Creator Features.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class BlockEntityBlockComponent extends BlockComponent implements ComponentFormatFeatureProvider
{
    public function __construct(
        private readonly bool $dynamicProperties = false,
        private readonly ?int $containerSlotCount = null,
        private readonly ?string $containerTitle = null,
        private readonly bool $entityStorage = false,
    ) {
        if ($containerSlotCount !== null && ($containerSlotCount < 1 || $containerSlotCount > 54)) {
            throw new InvalidArgumentException("Container slot count must be between 1 and 54.");
        }
        if ($containerTitle !== null) {
            if ($containerSlotCount === null) {
                throw new InvalidArgumentException("A container title requires a container slot count.");
            }
            $length = mb_strlen($containerTitle);
            if ($length < 1 || $length > 256) {
                throw new InvalidArgumentException("Container title must be between 1 and 256 characters.");
            }
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

    public function getBedrockFormatFeatures(): array
    {
        $features = [BlockComponentIds::BLOCK_ENTITY->value];
        if ($this->containerTitle !== null) {
            $features[] = BlockComponentIds::BLOCK_ENTITY->value . '.container.title';
        }
        if ($this->entityStorage) {
            $features[] = BlockComponentIds::BLOCK_ENTITY->value . '.entity_storage';
        }
        return $features;
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

        if ($this->entityStorage) {
            $nbt->setTag("entity_storage", new ByteTag(1));
        }

        if ($this->containerSlotCount !== null) {
            $container = CompoundTag::create()
                ->setTag("slot_count", new IntTag($this->containerSlotCount));
            if ($this->containerTitle !== null) {
                $container->setTag("title", new StringTag($this->containerTitle));
            }
            $nbt->setTag("container", $container);
        }

        return $nbt;
    }
}
