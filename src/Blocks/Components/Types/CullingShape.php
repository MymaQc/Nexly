<?php

namespace Nexly\Blocks\Components\Types;

/**
 * Built-in voxel culling shapes. Custom resource-pack names are also accepted.
 */
enum CullingShape: string
{
    case EMPTY = "minecraft:empty";
    case UNIT_CUBE = "minecraft:unit_cube";
}
