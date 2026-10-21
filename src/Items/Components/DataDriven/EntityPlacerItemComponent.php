<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;
use Nexly\Blocks\Components\Types\BlockDescriptor;

#[Attribute(Attribute::TARGET_CLASS)]
class EntityPlacerItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $entity
     * @param list<string|BlockDescriptor> $dispenseOn
     * @param list<string|BlockDescriptor> $useOn
     */
    public function __construct(
        private readonly string $entity,
        private readonly array $dispenseOn = [],
        private readonly array $useOn = [],
    ) {
        foreach (["use_on" => $useOn, "dispense_on" => $dispenseOn] as $label => $filters) {
            if (!array_is_list($filters)) {
                throw new InvalidArgumentException("Entity placer $label filters must be lists.");
            }
            foreach ($filters as $filter) {
                if ((!\is_string($filter) && !$filter instanceof BlockDescriptor) || $filter === "") {
                    throw new InvalidArgumentException("Entity placer filters must be non-empty block identifiers or BlockDescriptor objects.");
                }
            }
        }
        if ($entity === "") {
            throw new InvalidArgumentException("Entity identifier cannot be empty.");
        }

    }

    /**
     * Create an EntityPlacerItemComponent from the given parameters.
     *
     * @param string $entity
     * @param list<Block> $dispenseOn
     * @param list<Block> $useOn
     * @return self
     */
    public static function from(string $entity, array $dispenseOn = [], array $useOn = []): self
    {
        return new self(
            $entity,
            array_map(
                static fn (Block $block): string => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
                $dispenseOn
            ),
            array_map(
                static fn (Block $block): string => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
                $useOn
            )
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::ENTITY_PLACER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("entity", new StringTag($this->entity))
            ->setTag("dispense_on", new ListTag(array_map(
                static fn (BlockDescriptor|string $block): CompoundTag => $block instanceof BlockDescriptor ? $block->toNBT() : new BlockDescriptor($block)->toNBT(),
                $this->dispenseOn
            ), NBT::TAG_Compound))
            ->setTag("use_on", new ListTag(array_map(
                static fn (BlockDescriptor|string $block): CompoundTag => $block instanceof BlockDescriptor ? $block->toNBT() : new BlockDescriptor($block)->toNBT(),
                $this->useOn
            ), NBT::TAG_Compound));
    }
}
