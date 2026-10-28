<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use InvalidArgumentException;
use Nexly\Items\Components\DataDriven\Types\SoundEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_swing_sounds?view=minecraft-bedrock-stable
 * @since 1.21.130
 */
#[Attribute(Attribute::TARGET_CLASS)]
class SwingSoundsItemComponent extends DataDrivenItemComponent
{
    public function __construct(
        private readonly SoundEvent|string $hit,
        private readonly SoundEvent|string $miss,
        private readonly SoundEvent|string $critical,
    ) {
        foreach ([$hit, $miss, $critical] as $sound) {
            // Since format 1.26.50, any sound_definitions.json name is accepted besides the built-in events
            if ($sound === "") {
                throw new InvalidArgumentException("Swing sound name cannot be empty.");
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
        return DataDrivenComponentIds::SWING_SOUNDS->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("attack_hit", new StringTag($this->hit instanceof SoundEvent ? $this->hit->value : $this->hit))
            ->setTag("attack_miss", new StringTag($this->miss instanceof SoundEvent ? $this->miss->value : $this->miss))
            ->setTag("attack_critical_hit", new StringTag($this->critical instanceof SoundEvent ? $this->critical->value : $this->critical));
    }
}
