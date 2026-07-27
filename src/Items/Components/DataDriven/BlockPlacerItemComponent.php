<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

// PlanterItemComponent in v1.21.111
#[Attribute(Attribute::TARGET_CLASS)]
class BlockPlacerItemComponent extends DataDrivenItemComponent
{
    /**
     * @param string $block
     * @param list<string> $useOn
     * @param bool $alignedPlacement
     * @param bool $replaceBlockItem
     */
    public function __construct(
        private readonly string $block,
        private readonly array $useOn = [],
        private readonly bool $alignedPlacement = false,
        private readonly bool $replaceBlockItem = false,
    ) {
        if ($block === "") {
            throw new \InvalidArgumentException("Placed block identifier cannot be empty.");
        }

        if (count($useOn) > 256) {
            throw new \InvalidArgumentException("Use-on filters cannot contain more than 256 entries.");
        }

        foreach ($useOn as $filter) {
            if ($filter === "") {
                throw new \InvalidArgumentException("Use-on filters must contain non-empty block identifiers.");
            }
        }
    }

    /**
     * Create a BlockPlacerItemComponent from a Block instance.
     *
     * @param Block $block
     * @param bool $alignedPlacement
     * @param bool $replaceBlockItem
     * @param Block ...$useOn
     * @return self
     */
    public static function from(
        Block $block,
        bool $alignedPlacement = false,
        bool $replaceBlockItem = false,
        Block ...$useOn
    ): self {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
            array_values(array_map(
                fn (Block $b): string => GlobalBlockStateHandlers::getSerializer()->serialize($b->getStateId())->getName(),
                $useOn
            )),
            $alignedPlacement,
            $replaceBlockItem
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::BLOCK_PLACER->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("block", new StringTag($this->block))
            ->setTag("aligned_placement", new ByteTag($this->alignedPlacement ? 1 : 0))
            ->setTag("replace_block_item", new ByteTag($this->replaceBlockItem ? 1 : 0))
            ->setTag("use_on", new ListTag(array_map(fn (string $block): StringTag => new StringTag($block), $this->useOn), NBT::TAG_String));
    }
}
