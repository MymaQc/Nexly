<?php

namespace Nexly\Items\Components\DataDriven\Property;

use Attribute;
use InvalidArgumentException;
use pocketmine\nbt\tag\IntTag;

#[Attribute(Attribute::TARGET_CLASS)]
class DamageProperty extends PropertyItemComponent
{
    public function __construct(
        private readonly int $damage
    ) {
        if ($this->damage < 0 || $this->damage > 32767) {
            throw new InvalidArgumentException("Damage must be between 0 and 32767 (Bedrock 1.26.0+).");
        }
    }

    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return PropertyComponentIds::DAMAGE->getValue();
    }

    /**
     * @return IntTag
     */
    public function toNBT(): IntTag
    {
        return new IntTag($this->damage);
    }
}
