<?php

namespace Nexly\Blocks\Permutations;

use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

readonly class BlockProperty
{
    /**
     * @var non-empty-list<string|int|bool>
     */
    private array $values;

    /**
     * @param string $name
     * @param list<string|int|bool> $values
     */
    public function __construct(
        private string $name,
        array          $values
    ) {
        if ($values === []) {
            throw new InvalidArgumentException("A block property must define at least one possible value.");
        }

        if (!array_is_list($values) || count($values) > 16) {
            throw new InvalidArgumentException("Block states require a list of at most 16 values.");
        }
        if ($name === "") {
            throw new InvalidArgumentException("Block state name cannot be empty.");
        }
        $type = get_debug_type($values[0]);
        $seen = [];
        foreach ($values as $value) {
            if ((!is_string($value) && !is_int($value) && !is_bool($value)) || get_debug_type($value) !== $type) {
                throw new InvalidArgumentException("Block state values must all have the same string, integer or boolean type.");
            }
            if (is_int($value) && ($value < -2147483648 || $value > 2147483647)) {
                throw new InvalidArgumentException("Block state integers must fit an NBT IntTag.");
            }
            if (in_array($value, $seen, true)) {
                throw new InvalidArgumentException("Block state values must be unique.");
            }
            $seen[] = $value;
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
        /**
         * @var list<StringTag|IntTag|ByteTag> $values
         */
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
