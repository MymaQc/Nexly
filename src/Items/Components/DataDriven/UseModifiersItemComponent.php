<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use Nexly\Items\Components\DataDriven\Types\ItemStartUsing;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class UseModifiersItemComponent extends DataDrivenItemComponent
{
    /**
     * @param float $useDuration
     * @param float|null $movementModifier
     * @param bool $emitVibrations
     * @param string|null $startSound
     * @param ItemStartUsing $startUsing
     */
    public function __construct(
        private readonly float $useDuration,
        private readonly ?float $movementModifier = null,
        private readonly bool $emitVibrations = true,
        private readonly ?string $startSound = null,
        private readonly ItemStartUsing $startUsing = ItemStartUsing::ALWAYS,
    ) {
        if ($useDuration < 0.0) {
            throw new \InvalidArgumentException("Use duration cannot be negative.");
        }

        if ($movementModifier !== null && ($movementModifier < 0.0 || $movementModifier > 1.0)) {
            throw new \InvalidArgumentException("Movement modifier must be between 0 and 1.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::USE_MODIFIERS->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $nbt = CompoundTag::create()
            ->setTag("use_duration", new FloatTag($this->useDuration))
            ->setTag("emit_vibrations", new ByteTag($this->emitVibrations ? 1 : 0))
            ->setTag("start_using", new StringTag($this->startUsing->getValue()));

        if ($this->movementModifier !== null) {
            $nbt->setTag("movement_modifier", new FloatTag($this->movementModifier));
        }

        if ($this->startSound !== null) {
            $nbt->setTag("start_sound", new StringTag($this->startSound));
        }

        return $nbt;
    }
}
