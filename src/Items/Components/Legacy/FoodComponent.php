<?php

namespace Nexly\Items\Components\Legacy;

use Nexly\Validation\ComponentValues;

use Attribute;
use BackedEnum;
use InvalidArgumentException;
use Nexly\Items\Components\Legacy\Types\LegacyEffect;
use Nexly\Items\Components\Legacy\Types\LegacyItemCooldownType;
use Nexly\Items\Components\Legacy\Types\LegacyItemUseActionType;
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;

#[Attribute(Attribute::TARGET_CLASS)]
class FoodComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::FOOD->getValue();
    }

    /**
     * @param int $nutrition
     * @param float $saturationModifier
     * @param bool $canAlwaysEat
     * @param LegacyItemCooldownType|BackedEnum|string $cooldownType
     * @param int $cooldownTick
     * @param string $usingConvertsTo
     * @param LegacyItemUseActionType $useActionType
     * @param list<float> $onUseRange
     * @param list<LegacyEffect> $effects
     * @param list<int> $removeEffects
     */
    public function __construct(
        private readonly int                                      $nutrition,
        private readonly float                                    $saturationModifier,
        private readonly bool                                     $canAlwaysEat = false,
        private readonly LegacyItemCooldownType|BackedEnum|string $cooldownType = LegacyItemCooldownType::NONE,
        private readonly int                                      $cooldownTick = 0,
        private string                                            $usingConvertsTo = "air",
        private readonly LegacyItemUseActionType                  $useActionType = LegacyItemUseActionType::NONE,
        private readonly array                                    $onUseRange = [],
        private readonly array                                    $effects = [],
        private readonly array                                    $removeEffects = [],
    ) {
        ComponentValues::finite($saturationModifier);
        ComponentValues::objects($effects, LegacyEffect::class);
        if (!array_is_list($onUseRange) || !array_is_list($removeEffects)) {
            throw new InvalidArgumentException("Legacy food collections must be lists.");
        }
        foreach ($onUseRange as $value) {
            if (!\is_float($value) && !\is_int($value)) {
                throw new InvalidArgumentException("Legacy food ranges must contain numbers.");
            }
            ComponentValues::finite((float) $value);
        }
        foreach ($removeEffects as $effectId) {
            if (!\is_int($effectId) || $effectId < 0 || $effectId > 2147483647) {
                throw new InvalidArgumentException("Legacy effect ids must be non-negative NBT ints.");
            }
        }
        if ($nutrition < 0 || $nutrition > 2147483647 || $cooldownTick < 0 || $cooldownTick > 2147483647) {
            throw new InvalidArgumentException("Legacy food values must be non-negative NBT ints.");
        }
        if ($saturationModifier < 0.0 || $saturationModifier > 1.0) {
            throw new InvalidArgumentException("Legacy saturation modifier must be between 0.0 and 1.0.");
        }
        if ($cooldownType === "") {
            throw new InvalidArgumentException("Cooldown type cannot be empty.");
        }
        if ($cooldownType instanceof BackedEnum && !\is_string($cooldownType->value)) {
            throw new InvalidArgumentException("The cooldown type enum must be string-backed.");
        }
    }

    /**
     * Set the item that this food converts to when used.
     *
     * @param Item $item
     */
    public function setUsingConvertsTo(Item $item): void
    {
        $converter = TypeConverter::getInstance();
        [$runtimeId] = $converter->getItemTranslator()->toNetworkId($item);
        $this->usingConvertsTo = $converter->getItemTypeDictionary()->fromIntId($runtimeId);
    }

    /**
     * Get the maximum damage value.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        if ($this->usingConvertsTo === "") {
            throw new InvalidArgumentException("Converted item identifier cannot be empty.");
        }
        ComponentValues::objects($this->effects, LegacyEffect::class);
        $cooldownType = \is_string($this->cooldownType) ? $this->cooldownType : $this->cooldownType->value;
        if (!\is_string($cooldownType)) {
            throw new InvalidArgumentException("The cooldown type enum must be backed by a string.");
        }

        return CompoundTag::create()
            ->setTag("nutrition", new IntTag($this->nutrition))
            ->setTag("saturation_modifier", new FloatTag($this->saturationModifier))
            ->setTag("can_always_eat", new ByteTag($this->canAlwaysEat ? 1 : 0))
            ->setTag("cooldown_type", new StringTag($cooldownType))
            ->setTag("cooldown_time", new IntTag($this->cooldownTick))
            ->setTag("using_converts_to", CompoundTag::create()->setTag("name", new StringTag($this->usingConvertsTo)))
            ->setTag("on_use_action", new IntTag($this->useActionType->getValue()))
            ->setTag("on_use_range", new ListTag(array_map(static fn (float $value): FloatTag => new FloatTag($value), $this->onUseRange), NBT::TAG_Float))
            ->setTag("effects", new ListTag(array_map(static fn (LegacyEffect $effect): CompoundTag => $effect->toNBT(), $this->effects), NBT::TAG_Compound))
            ->setTag("remove_effects", new ListTag(array_map(static fn (int $value): IntTag => new IntTag($value), $this->removeEffects), NBT::TAG_Int));
    }
}
