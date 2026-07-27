<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

readonly class ItemDescriptor
{
    private function __construct(
        private string $tags,
    ) {
        if ($tags === "") {
            throw new \InvalidArgumentException("Item descriptor tags cannot be empty.");
        }
    }

    public static function fromTags(string $molangQuery): self
    {
        return new self($molangQuery);
    }

    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("tags", new StringTag($this->tags));
    }
}
