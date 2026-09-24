<?php

namespace Nexly\Items\Components\DataDriven;

use Nexly\Validation\ComponentValues;

use Attribute;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class DiggerItemComponent extends DataDrivenItemComponent
{
    /**
     * @param list<CompoundTag> $destroySpeeds
     */
    public function __construct(
        private readonly bool $useEfficiency = false,
        private array $destroySpeeds = []
    ) {
        ComponentValues::objects($destroySpeeds, CompoundTag::class);
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DIGGER->getValue();
    }

    /**
     * Add a block with its corresponding speed to the digger component.
     *
     * @param Block $block
     * @param int $speed
     * @return $this
     */
    public function addBlock(Block $block, int $speed): self
    {
        if ($speed < 0 || $speed > 2147483647) {
            throw new InvalidArgumentException("Destroy speed must fit a non-negative NBT int.");
        }

        $blockName = GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName();
        $this->destroySpeeds[] = CompoundTag::create()
            ->setTag("block", CompoundTag::create()->setTag("name", new StringTag($blockName)))
            ->setTag("speed", new IntTag($speed));
        return $this;
    }

    /**
     * Add multiple blocks with the same speed to the digger component.
     *
     * @param int $speed
     * @param Block ...$blocks
     * @return $this
     */
    public function addBlocks(int $speed, Block ...$blocks): self
    {
        foreach ($blocks as $block) {
            $this->addBlock($block, $speed);
        }
        return $this;
    }

    /**
     * Add a tag with its corresponding speed to the digger component.
     *
     * @param list<string>|string $tags
     * @param int $speed
     * @return $this
     */
    public function addTag(array|string $tags, int $speed): self
    {
        $tags = \is_string($tags) ? [$tags] : $tags;
        ComponentValues::strings($tags);
        if ($tags === []) {
            throw new InvalidArgumentException("At least one block tag is required.");
        }
        foreach ($tags as $tag) {
            if (strpbrk($tag, "'\\\\") !== false) {
                throw new InvalidArgumentException("Block tags cannot contain quotes or backslashes in a Molang expression.");
            }
        }

        if ($speed < 0 || $speed > 2147483647) {
            throw new InvalidArgumentException("Destroy speed must fit a non-negative NBT int.");
        }

        $this->destroySpeeds[] = CompoundTag::create()
            ->setTag("block", CompoundTag::create()->setString("tags", "q.any_tag(" . implode(", ", array_map(static fn (string $tag): string => "'$tag'", $tags)) . ")"))
            ->setTag("speed", new IntTag($speed));
        return $this;
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        ComponentValues::objects($this->destroySpeeds, CompoundTag::class);
        return CompoundTag::create()
            ->setTag("use_efficiency", new ByteTag($this->useEfficiency ? 1 : 0))
            ->setTag("destroy_speeds", new ListTag($this->destroySpeeds, NBT::TAG_Compound));
    }
}
