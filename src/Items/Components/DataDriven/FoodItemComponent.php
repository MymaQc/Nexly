<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

#[Attribute(Attribute::TARGET_CLASS)]
class FoodItemComponent extends DataDrivenItemComponent
{
    /**
     * @param int $nutrition
     * @param float $saturationModifier
     * @param bool $canAlwaysEat
     * @param string|null $usingConvertsTo
     */
    public function __construct(
        private readonly int $nutrition,
        private readonly float $saturationModifier,
        private readonly bool $canAlwaysEat = false,
        private ?string $usingConvertsTo = null,
    ) {
        if ($nutrition < 0) {
            throw new \InvalidArgumentException("Nutrition cannot be negative.");
        }

        if ($saturationModifier < 0.0) {
            throw new \InvalidArgumentException("Saturation modifier cannot be negative.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::FOOD->getValue();
    }

    /**
     * Set the item that this food converts to when used.
     *
     * @param Item $item
     */
    public function setUsingConvertsTo(Item $item): void
    {
        [$rId] = ($converter = TypeConverter::getInstance())->getItemTranslator()->toNetworkId($item);
        if ($rId === null) {
            throw new \InvalidArgumentException("Item does not have a valid network ID");
        }

        $this->usingConvertsTo = $converter->getItemTypeDictionary()->fromIntId($rId);
    }

    /**
     * Get the maximum damage value.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("nutrition", new IntTag($this->nutrition))
            ->setTag("saturation_modifier", new FloatTag($this->saturationModifier))
            ->setTag("can_always_eat", new ByteTag($this->canAlwaysEat ? 1 : 0));

        if ($this->usingConvertsTo !== null) {
            $nbt->setTag("using_converts_to", CompoundTag::create()->setTag("name", new StringTag($this->usingConvertsTo)));
        }

        return $nbt;
    }
}
