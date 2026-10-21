<?php

namespace Nexly\Items\Components\DataDriven;

use Nexly\Validation\ComponentValues;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemRepair;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class RepairableItemComponent extends DataDrivenItemComponent
{
    public const string VANILLA_COST_FORMULE = "math.min(q.remaining_durability + c.other->q.remaining_durability + math.floor(q.max_durability /20), c.other->q.max_durability)";

    /**
     * @param list<ItemRepair> $repairItems
     */
    public function __construct(
        private readonly array $repairItems,
    ) {
        ComponentValues::objects($repairItems, ItemRepair::class);
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::REPAIRABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("repair_items", new ListTag(array_map(static fn (ItemRepair $item): CompoundTag => $item->toNBT(), $this->repairItems), NBT::TAG_Compound));
    }
}
