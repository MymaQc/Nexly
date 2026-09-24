<?php

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use Nexly\Validation\ComponentValues;
use pocketmine\math\Vector3;

class RangeOffset
{
    public function __construct(
        private readonly Vector3 $min,
        private readonly Vector3 $max,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        foreach (['getX', 'getY', 'getZ'] as $axis) {
            ComponentValues::finite($this->min->$axis(), $this->max->$axis());
            if ($this->min->$axis() > $this->max->$axis()) {
                throw new InvalidArgumentException("Offset ranges must be finite and ordered on each axis.");
            }
        }
    }

    /**
     * @return Vector3
     */
    public function getMin(): Vector3
    {
        return $this->min;
    }

    /**
     * @return Vector3
     */
    public function getMax(): Vector3
    {
        return $this->max;
    }
}
