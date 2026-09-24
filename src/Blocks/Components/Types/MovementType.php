<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

enum MovementType: string
{
    case PUSH_PULL = 'push_pull';
    case PUSH = 'push';
    case POPPED = 'popped';
    case IMMOVABLE = 'immovable';
}
