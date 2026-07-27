<?php

namespace Nexly\Items\Components\DataDriven\Types;

use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

class ItemRepair
{
    /**
     * @param float|string $repairAmount
     * @param list<string> $items
     */
    public function __construct(
        private readonly float|string $repairAmount,
        private readonly array $items,
    ) {
        if ($repairAmount === "") {
            throw new \InvalidArgumentException("Repair amount expression cannot be empty.");
        }

        if ($items === []) {
            throw new \InvalidArgumentException("At least one repair item is required.");
        }

        foreach ($items as $item) {
            if ($item === "") {
                throw new \InvalidArgumentException("Repair items must be non-empty item identifiers.");
            }
        }
    }

    /**
     * Create an ItemRepair component from an amount and a list of items.
     *
     * @param float|string $repairAmount
     * @param Item ...$items
     * @return ItemRepair
     */
    public static function from(float|string $repairAmount, Item ...$items): self
    {
        return new self(
            $repairAmount,
            array_values(array_map(function (Item $item): string {
                [$rid] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
                return $converter->getItemTypeDictionary()->fromIntId($rid);
            }, $items))
        );
    }

    /**
     * @return float|string
     */
    public function getAmount(): float|string
    {
        return $this->repairAmount;
    }

    public function toNBT(): CompoundTag
    {
        $repairAmount = is_float($this->repairAmount)
            ? new FloatTag($this->repairAmount)
            : CompoundTag::create()
                ->setTag("expression", new StringTag($this->repairAmount))
                ->setTag("version", new IntTag(12));

        return CompoundTag::create()
            ->setTag("repair_amount", $repairAmount)
            ->setTag("items", new ListTag(array_map(fn (string $stringId): StringTag => new StringTag($stringId), $this->items), NBT::TAG_String));
    }
}
