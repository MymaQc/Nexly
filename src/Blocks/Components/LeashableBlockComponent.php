<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use Nexly\Validation\ComponentValues;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;

/** Bedrock lead/balloon knot attachment offset, in model pixels (format 1.26.0+). */
#[Attribute(Attribute::TARGET_CLASS)]
final class LeashableBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly ?Vector3 $offset = null
    ) {
        if ($offset !== null) {
            ComponentValues::finite($offset->x, $offset->y, $offset->z);
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::LEASHABLE->value;
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create();
        if ($this->offset !== null) {
            ComponentValues::finite($this->offset->x, $this->offset->y, $this->offset->z);
            $tag->setTag('offset', new ListTag([
                new FloatTag($this->offset->x),
                new FloatTag($this->offset->y),
                new FloatTag($this->offset->z),
            ], NBT::TAG_Float));
        }
        return $tag;
    }

}
