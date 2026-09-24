<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LightEmissionBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly int $value,
    ) {
        if ($value < 0 || $value > 15) {
            throw new InvalidArgumentException("Light emission value must be between 0 and 15");
        }
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::LIGHT_EMISSION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("emission", new ByteTag($this->value));
    }
}
