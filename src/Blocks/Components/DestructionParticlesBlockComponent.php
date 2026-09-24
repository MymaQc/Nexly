<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\TintMethod;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

/**
 * Destruction particle texture and tint (format 1.21.80+; stable without experiments from 1.21.100).
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class DestructionParticlesBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly int $particleCount = 100,
        private readonly ?string $texture = null,
        private readonly TintMethod $tintMethod = TintMethod::NONE,
    ) {
        if ($particleCount < 0 || $particleCount > 255) {
            throw new InvalidArgumentException('Destruction particle count must be between 0 and 255.');
        }
        if ($texture === '') {
            throw new InvalidArgumentException('Particle texture cannot be empty when provided.');
        }
    }

    public function getName(): string
    {
        return BlockComponentIds::DESTRUCTION_PARTICLES->value;
    }

    public function toNBT(): CompoundTag
    {
        $tag = CompoundTag::create()
            ->setTag('particle_count', new IntTag($this->particleCount))
            ->setTag('tint_method', new StringTag($this->tintMethod->value));
        if ($this->texture !== null) {
            $tag->setString('texture', $this->texture);
        }
        return $tag;
    }
}
