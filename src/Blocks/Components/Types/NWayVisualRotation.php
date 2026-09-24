<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

/**
 * Axis to block-state mapping used by the current Bedrock JSON component.
 */
final readonly class NWayVisualRotation
{
    /**
     * @param array<string, string>|list<array{VisualRotationAxis|string, string}> $axes
     */
    private array $axes;

    public function __construct(array $axes)
    {
        if ($axes === [] || \count($axes) > 3) {
            throw new InvalidArgumentException('N-way rotation needs between one and three axes.');
        }
        $seen = [];
        $normalized = [];
        foreach ($axes as $axis => $state) {
            if (\is_int($axis)) {
                if (!\is_array($state) || \count($state) !== 2 || (!\is_string($state[0] ?? null) && !($state[0] ?? null) instanceof VisualRotationAxis)) {
                    throw new InvalidArgumentException('List entries must pair a rotation axis with its state name.');
                }
                [$axis, $state] = $state;
            }
            $key = $axis instanceof VisualRotationAxis ? $axis->value : $axis;
            if (isset($seen[$key]) || !\is_string($key) || VisualRotationAxis::tryFrom($key) === null) {
                throw new InvalidArgumentException('N-way rotation axes must be unique x, y, or z values.');
            }
            if (!\is_string($state) || $state === '' || \strlen($state) > 256) {
                throw new InvalidArgumentException('N-way rotation block state names must contain 1..256 bytes.');
            }
            $seen[$key] = $state;
            $normalized[$key] = $state;
        }
        $this->axes = $normalized;
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create();
        foreach ($this->axes as $axis => $state) {
            $tag->setTag($axis, new StringTag($state));
        }
        return $tag;
    }
}
