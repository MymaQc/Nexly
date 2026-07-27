<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

#[Attribute(Attribute::TARGET_CLASS)]
class StorageItemComponent extends DataDrivenItemComponent
{
    /**
     * @param int $maxSlots
     * @param bool $allowNestedStorageItems
     * @param list<string> $allowedItems
     * @param list<string> $bannedItems
     */
    public function __construct(
        private readonly int $maxSlots = 64,
        private readonly bool $allowNestedStorageItems = true,
        private readonly array $allowedItems = [],
        private readonly array $bannedItems = [],
    ) {
        if ($this->maxSlots < 0) {
            throw new \InvalidArgumentException("Maximum slots cannot be negative.");
        }

        if ($this->maxSlots > 64) {
            throw new \InvalidArgumentException("Maximum slots must not exceed 64.");
        }

        if ($this->allowedItems !== [] && $this->bannedItems !== []) {
            throw new \InvalidArgumentException("Allowed and banned item filters cannot be used together.");
        }

        foreach ([...$allowedItems, ...$bannedItems] as $item) {
            if ($item === "") {
                throw new \InvalidArgumentException("Storage item filters must contain non-empty item identifiers.");
            }
        }
    }

    /**
     * Create a StorageItemComponent.
     *
     * @param int $maxSlots
     * @param bool $allowNestedStorageItems
     * @param list<Item> $allowedItems
     * @param list<Item> $bannedItems
     * @return self
     */
    public static function from(
        int $maxSlots,
        bool $allowNestedStorageItems = true,
        array $allowedItems = [],
        array $bannedItems = []
    ): self {

        $processItem = function (Item $item): string {
            [$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
            return $converter->getItemTypeDictionary()->fromIntId($rid);
        };

        return new self(
            $maxSlots,
            $allowNestedStorageItems,
            array_map($processItem, $allowedItems),
            array_map($processItem, $bannedItems)
        );
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::STORAGE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("max_slots", new IntTag($this->maxSlots))
            ->setTag("allow_nested_storage_items", new ByteTag($this->allowNestedStorageItems ? 1 : 0))
            ->setTag("allowed_items", new ListTag(array_map(fn (string $item): StringTag => new StringTag($item), $this->allowedItems), NBT::TAG_String))
            ->setTag("banned_items", new ListTag(array_map(fn (string $item): StringTag => new StringTag($item), $this->bannedItems), NBT::TAG_String));
    }
}
