<?php

namespace Nexly\Blocks\Components\Types;

/**
 * Whether adjacent lava can ignite this block (Bedrock format 1.26.30+).
 */
enum LavaFlammability: string
{
    case ALWAYS = "always";
    case NEVER = "never";
}
