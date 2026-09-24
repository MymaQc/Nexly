<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

/**
 * Faces accepted by minecraft:placement_filter, including the documented aliases.
 */
enum PlacementFace: string
{
    case DOWN = 'down';
    case UP = 'up';
    case NORTH = 'north';
    case SOUTH = 'south';
    case WEST = 'west';
    case EAST = 'east';
    case SIDE = 'side';
    case ALL = 'all';
}
