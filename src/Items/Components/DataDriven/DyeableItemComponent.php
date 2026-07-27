<?php

namespace Nexly\Items\Components\DataDriven;

use Attribute;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DyeableItemComponent extends DataDrivenItemComponent
{
    private readonly string $defaultColor;

    /**
     * @param string|array{int, int, int} $defaultColor
     */
    public function __construct(
        string|array $defaultColor = "#FFFFFF",
    ) {
        if (is_array($defaultColor)) {
            if (array_filter($defaultColor, fn (int $channel): bool => $channel < 0 || $channel > 255) !== []) {
                throw new \InvalidArgumentException("RGB color must contain three integer channels between 0 and 255.");
            }

            $defaultColor = sprintf("#%02X%02X%02X", ...$defaultColor);
        }

        if (!preg_match('/^#[A-Fa-f0-9]{6}$/', $defaultColor)) {
            throw new \InvalidArgumentException("Invalid color format. Use #RRGGBB.");
        }

        $this->defaultColor = $defaultColor;
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return DataDrivenComponentIds::DYEABLE->getValue();
    }

    /**
     * Build the NBT tag for this component.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("default_color", new StringTag($this->defaultColor));
    }
}
