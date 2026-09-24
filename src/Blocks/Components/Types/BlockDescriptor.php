<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/**
 * A vanilla block descriptor selected by name/states or a Molang tag expression.
 */
final readonly class BlockDescriptor
{
    /**
     * @param array<string, bool|int|string> $states
     */
    public function __construct(
        private ?string $name = null,
        private array $states = [],
        private ?string $tags = null,
    ) {
        if (($name === null || $name === '') && ($tags === null || $tags === '')) {
            throw new InvalidArgumentException('A block descriptor needs a name or a tag expression.');
        }
        if ($tags !== null && $name !== null) {
            throw new InvalidArgumentException('A block descriptor uses either name/states or tags.');
        }
        foreach ($states as $key => $value) {
            if (!\is_string($key) || $key === '' || !(\is_bool($value) || \is_int($value) || \is_string($value))) {
                throw new InvalidArgumentException('Block descriptor states require named string, integer, or boolean values.');
            }
            if (\is_int($value) && ($value < -2147483648 || $value > 2147483647)) {
                throw new InvalidArgumentException('Block descriptor integer states must fit an NBT int.');
            }
        }
        if ($name === null && $states !== []) {
            throw new InvalidArgumentException('Block states require a block name.');
        }
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create();
        if ($this->name !== null) {
            $tag->setString('name', $this->name);
        }
        if ($this->tags !== null) {
            $tag->setString('tags', $this->tags);
        }
        if ($this->states !== []) {
            $states = CompoundTag::create();
            foreach ($this->states as $name => $value) {
                $states->setTag($name, match (true) {
                    \is_bool($value) => new ByteTag($value ? 1 : 0),
                    \is_int($value) => new IntTag($value),
                    default => new StringTag($value),
                });
            }
            $tag->setTag('states', $states);
        }
        return $tag;
    }
}
