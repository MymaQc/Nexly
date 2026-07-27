<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class ThrowableItemComponent extends DataDrivenItemComponent
{
    /**
     * @param bool $doSwingAnimation
     * @param float $launchPowerScale
     * @param float $maxDrawDuration
     * @param float $maxLaunchPower
     * @param float $minDrawDuration
     * @param bool $scalePowerByDrawDuration
     */
    public function __construct(
        private readonly bool  $doSwingAnimation = false,
        private readonly float $launchPowerScale = 1.0,
        private readonly float $maxDrawDuration = 0.0,
        private readonly float $maxLaunchPower = 1.0,
        private readonly float $minDrawDuration = 0.0,
        private readonly bool  $scalePowerByDrawDuration = false
    ) {
        if (
            $launchPowerScale < 0.0
            || $maxDrawDuration < 0.0
            || $maxLaunchPower < 0.0
            || $minDrawDuration < 0.0
        ) {
            throw new \InvalidArgumentException("Throwable power and duration values cannot be negative.");
        }

        if ($minDrawDuration > $maxDrawDuration) {
            throw new \InvalidArgumentException("Minimum draw duration cannot exceed maximum draw duration.");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::THROWABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("do_swing_animation", new ByteTag($this->doSwingAnimation ? 1 : 0))
            ->setTag("launch_power_scale", new FloatTag($this->launchPowerScale))
            ->setTag("max_draw_duration", new FloatTag($this->maxDrawDuration))
            ->setTag("max_launch_power", new FloatTag($this->maxLaunchPower))
            ->setTag("min_draw_duration", new FloatTag($this->minDrawDuration))
            ->setTag("scale_power_by_draw_duration", new ByteTag($this->scalePowerByDrawDuration ? 1 : 0));
    }
}
