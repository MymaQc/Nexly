<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\StringTag;

/** Behavior-pack loot-table path, relative to the pack root (max 256 characters). */
#[Attribute(Attribute::TARGET_CLASS)]
final class LootBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly string $path
    ) {
        if ($path === '' || \strlen($path) > 256 || str_contains($path, "\\") || str_starts_with($path, '/')) {
            throw new InvalidArgumentException('Loot table path must be a relative pack path of at most 256 characters.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::LOOT->value;
    }

    public function toNBT(): StringTag
    {
        return new StringTag($this->path);
    }
}
