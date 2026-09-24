<?php

namespace Nexly\Items\Components\DataDriven;

use Nexly\Validation\ComponentValues;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_swing_duration?view=minecraft-bedrock-stable
 * @since 1.21.120
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SwingDurationItemComponent extends DataDrivenItemComponent
{
    /**
     * @param float $duration Visual swing animation duration in seconds; does not control attack rate.
     */
    public function __construct(
        private readonly float $duration = 0.3,
    ) {
        ComponentValues::finite($duration);
        if ($duration < 0.0) {
            throw new InvalidArgumentException("Swing duration cannot be negative.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::SWING_DURATION->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new FloatTag($this->duration));
    }
}
