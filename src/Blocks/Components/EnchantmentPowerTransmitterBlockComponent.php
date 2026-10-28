<?php

namespace Nexly\Blocks\Components;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

/**
 * minecraft:enchantment_power_transmitter - when true, an enchanting table stays powered by a bookshelf
 * even if this block sits in the 1 block gap between them. Blocks do not transmit enchantment power by default.
 * Requires format version 1.26.60 and the Upcoming Creator Features experiment.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class EnchantmentPowerTransmitterBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly bool $transmit = true,
    ) {
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::ENCHANTMENT_POWER_TRANSMITTER->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new ByteTag($this->transmit ? 1 : 0));
    }
}
