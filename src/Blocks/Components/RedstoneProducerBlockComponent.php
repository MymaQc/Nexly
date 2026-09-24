<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\BlockFace;
use Nexly\Validation\ComponentValues;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

/**
 * Static redstone output (format 1.21.120+).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class RedstoneProducerBlockComponent extends BlockComponent
{
    /**
     * @var list<BlockFace>
     */
    private readonly array $connectedFaces;

    /**
     * @param list<BlockFace>|null $connectedFaces Defaults to all six faces, as in Bedrock.
     */
    public function __construct(
        private readonly int $power,
        ?array $connectedFaces = null,
        private readonly ?BlockFace $stronglyPoweredFace = null,
        private readonly bool $transformRelative = false,
    ) {
        if ($power < 0 || $power > 15) {
            throw new InvalidArgumentException('Redstone power must be from 0 to 15.');
        }
        $this->connectedFaces = $connectedFaces ?? BlockFace::cases();
        ComponentValues::objects($this->connectedFaces, BlockFace::class);
        if (\count(array_unique(array_map(static fn (BlockFace $face): string => $face->value, $this->connectedFaces))) !== \count($this->connectedFaces)) {
            throw new InvalidArgumentException('Connected redstone faces must be unique.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::REDSTONE_PRODUCER->value;
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create()
            ->setTag('power', new IntTag($this->power))
            ->setTag('connected_faces', new ListTag(array_map(static fn (BlockFace $face): StringTag => new StringTag($face->value), $this->connectedFaces), NBT::TAG_String))
            ->setTag('transform_relative', new ByteTag($this->transformRelative ? 1 : 0));
        if ($this->stronglyPoweredFace !== null) {
            $tag->setString('strongly_powered_face', $this->stronglyPoweredFace->value);
        }
        return $tag;
    }
}
