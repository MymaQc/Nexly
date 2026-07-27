<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class TagsItemComponent extends DataDrivenItemComponent
{
    /**
     * @param list<string> $tags
     */
    public function __construct(
        private readonly array $tags,
    ) {
        foreach ($tags as $tag) {
            if ($tag === "") {
                throw new \InvalidArgumentException("Item tags must contain only non-empty strings.");
            }
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::TAGS->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("tags", new ListTag(array_map(fn (string $tag): StringTag => new StringTag($tag), $this->tags), NBT::TAG_String));
    }
}
