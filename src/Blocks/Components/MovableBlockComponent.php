<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\MovementType;
use Nexly\Blocks\Components\Types\StickyType;
use pocketmine\nbt\tag\CompoundTag;

/**
 * Piston behavior (format 1.21.100+).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class MovableBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly MovementType $movementType = MovementType::PUSH_PULL,
        private readonly ?StickyType $sticky = null
    ) {
        if ($sticky === StickyType::SAME && $movementType !== MovementType::PUSH_PULL) {
            throw new InvalidArgumentException('Sticky type SAME only works with push_pull movement.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::MOVABLE->value;
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create()->setString('movement_type', $this->movementType->value);
        if ($this->sticky !== null) {
            $tag->setString('sticky', $this->sticky->value);
        }
        return $tag;
    }
}
