<?php

namespace Nexly\Blocks\Components\Types;

use pocketmine\math\Vector3;

class RangeOffset
{
    public function __construct(
        private readonly Vector3 $min,
        private readonly Vector3 $max,
    ) {
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
