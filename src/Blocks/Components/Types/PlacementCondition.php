<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

/**
 * One OR alternative in minecraft:placement_filter.conditions.
 */
final readonly class PlacementCondition
{
    /**
     * @param list<PlacementFace|string> $allowedFaces @param list<BlockDescriptor|string> $blockFilter
     */
    public function __construct(private array $allowedFaces = [], private array $blockFilter = [])
    {
        if ($allowedFaces === [] && $blockFilter === []) {
            throw new InvalidArgumentException('A placement condition needs allowed faces or block filters.');
        }
        if (!array_is_list($allowedFaces) || \count($allowedFaces) > 6) {
            throw new InvalidArgumentException('Allowed faces must be a list with at most six entries.');
        }
        $faces = [];
        foreach ($allowedFaces as $face) {
            if ($face instanceof PlacementFace) {
                $value = $face->value;
            } elseif (\is_string($face) && PlacementFace::tryFrom($face) !== null) {
                $value = $face;
            } else {
                throw new InvalidArgumentException('Unknown placement face.');
            }
            if (isset($faces[$value])) {
                throw new InvalidArgumentException('Placement faces cannot be repeated.');
            }
            $faces[$value] = true;
        }
        if (!array_is_list($blockFilter) || \count($blockFilter) > 64) {
            throw new InvalidArgumentException('Block filters must be a list with at most 64 descriptors.');
        }
        foreach ($blockFilter as $filter) {
            if (!($filter instanceof BlockDescriptor) && (!\is_string($filter) || $filter === '')) {
                throw new InvalidArgumentException('Block filters must be non-empty identifiers or BlockDescriptor objects.');
            }
        }
    }

    public function toNBT(): CompoundTag
    {
        $condition = CompoundTag::create();
        if ($this->allowedFaces !== []) {
            $condition->setTag('allowed_faces', new ListTag(array_map(static fn (PlacementFace|string $face): StringTag => new StringTag($face instanceof PlacementFace ? $face->value : $face), $this->allowedFaces), NBT::TAG_String));
        }
        if ($this->blockFilter !== []) {
            $condition->setTag('block_filter', new ListTag(array_map(static fn (BlockDescriptor|string $filter): CompoundTag => $filter instanceof BlockDescriptor ? $filter->toNBT() : new BlockDescriptor($filter)->toNBT(), $this->blockFilter), NBT::TAG_Compound));
        }
        return $condition;
    }
}
