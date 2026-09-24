<?php

namespace Nexly\Blocks\Components\Types;

/**
 * Built-in block face-culling layers. Custom resource-pack names are also accepted.
 */
enum CullingLayer: string
{
    case UNDEFINED = "minecraft:culling_layer.undefined";
    case LEAVES = "minecraft:culling_layer.leaves";
}
