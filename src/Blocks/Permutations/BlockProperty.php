<?php

namespace Nexly\Blocks\Permutations;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class BlockProperty
{
    /** @var non-empty-list<string|int|bool> */
    private readonly array $values;

    /**
     * @param string $name
     * @param list<string|int|bool> $values
     */
    public function __construct(
        private readonly string $name,
        array $values
    ) {
        if ($values === []) {
            throw new \InvalidArgumentException("A block property must define at least one possible value.");
        }

        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-list<string|int|bool>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function toNBT(): CompoundTag
    {
        /** @var list<StringTag|IntTag|ByteTag> $values */
        $values = [];
        foreach ($this->values as $value) {
            if (is_string($value)) {
                $values[] = new StringTag($value);
            } elseif (is_int($value)) {
                $values[] = new IntTag($value);
            } else {
                $values[] = new ByteTag($value ? 1 : 0);
            }
        }

        return CompoundTag::create()
            ->setTag("name", new StringTag($this->name))
            ->setTag("enum", new ListTag($values));
    }
}
