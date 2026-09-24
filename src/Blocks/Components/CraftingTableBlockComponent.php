<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Validation\ComponentValues;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

/**
 * Custom crafting UI and recipe tags (behavior-pack format 1.19.50+).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class CraftingTableBlockComponent extends BlockComponent
{
    /**
     * @param list<string> $craftingTags
     */
    public function __construct(private readonly array $craftingTags, private readonly ?string $tableName = null)
    {
        ComponentValues::strings($craftingTags);
        if (\count($craftingTags) > 64) {
            throw new InvalidArgumentException('At most 64 crafting tags are allowed.');
        }
        foreach ($craftingTags as $tag) {
            if (\strlen($tag) > 64) {
                throw new InvalidArgumentException('Crafting tags are limited to 64 characters.');
            }
        }
        if ($tableName === '') {
            throw new InvalidArgumentException('Crafting table name cannot be empty when provided.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::CRAFTING_TABLE->value;
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create()->setTag('crafting_tags', new ListTag(array_map(static fn (string $value): StringTag => new StringTag($value), $this->craftingTags), NBT::TAG_String));
        if ($this->tableName !== null) {
            $tag->setString('table_name', $this->tableName);
        }
        return $tag;
    }
}
