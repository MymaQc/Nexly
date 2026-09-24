<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\TintMethod;
use Nexly\Compatibility\ComponentFormatFeatureProvider;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * Map color, optionally biome-tinted (tint_method requires format 1.21.80+).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class MapColorBlockComponent extends BlockComponent implements ComponentFormatFeatureProvider
{
    private readonly string $hexColor;

    /**
     * @param string|array{int,int,int} $color Hex #RRGGBB or three RGB channels.
     */
    public function __construct(
        string|array $color,
        private readonly ?TintMethod $tintMethod = null
    ) {
        if (\is_array($color)) {
            if (!array_is_list($color) || \count($color) !== 3) {
                throw new InvalidArgumentException('Map color RGB must contain exactly three integer channels.');
            }
            foreach ($color as $channel) {
                if (!\is_int($channel) || $channel < 0 || $channel > 255) {
                    throw new InvalidArgumentException('Map color channels must be integers from 0 to 255.');
                }
            }
            $this->hexColor = \sprintf('#%02X%02X%02X', ...$color);
        } else {
            if (preg_match('/^#[0-9a-fA-F]{6}$/D', $color) !== 1) {
                throw new InvalidArgumentException('Map color must use the #RRGGBB format.');
            }
            $this->hexColor = strtoupper($color);
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::MAP_COLOR->value;
    }

    public function getBedrockFormatFeatures(): array
    {
        return $this->tintMethod === null
            ? [BlockComponentIds::MAP_COLOR->value]
            : [BlockComponentIds::MAP_COLOR->value, BlockComponentIds::MAP_COLOR->value . '.tint_method'];
    }

    public function toNBT(): StringTag|CompoundTag
    {
        if ($this->tintMethod === null) {
            return new StringTag($this->hexColor);
        }
        return CompoundTag::create()
            ->setTag('color', new StringTag($this->hexColor))
            ->setTag('tint_method', new StringTag($this->tintMethod->value));
    }
}
