<?php

namespace Nexly\Blocks\Components\Types;

/**
 * The six physical faces; unlike placement filters this excludes "side" and "all".
 */
enum BlockFace: string
{
    case DOWN = "down";
    case UP = "up";
    case NORTH = "north";
    case SOUTH = "south";
    case WEST = "west";
    case EAST = "east";

    public function getValue(): string
    {
        return $this->value;
    }
}
