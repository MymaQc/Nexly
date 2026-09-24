<?php

namespace Nexly\Blocks\Components;

use Nexly\Validation\ComponentValues;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;

#[Attribute(Attribute::TARGET_CLASS)]
class FrictionBlockComponent extends BlockComponent
{
    public function __construct(
        private readonly float $value = 0.0,
    ) {
        ComponentValues::finite($value);
        if ($this->value < 0.0 || $this->value > 0.9) {
            throw new InvalidArgumentException("Friction value must be between 0.0 and 0.9");
        }
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::FRICTION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("value", new FloatTag($this->value));
    }
}
