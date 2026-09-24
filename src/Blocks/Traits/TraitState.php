<?php

declare(strict_types=1);

namespace Nexly\Blocks\Traits;

/**
 * States selectable by the two placement traits.
 */
enum TraitState: string
{
    case CARDINAL_DIRECTION = 'cardinal_direction';
    case FACING_DIRECTION = 'facing_direction';
    case BLOCK_FACE = 'block_face';
    case VERTICAL_HALF = 'vertical_half';
    case SIXTEEN_WAY_ROTATION = 'sixteen_way_rotation';
    case CORNER_AND_CARDINAL_DIRECTION = 'corner_and_cardinal_direction';

    public function getTrait(): TraitIds
    {
        return match ($this) {
            self::BLOCK_FACE, self::VERTICAL_HALF => TraitIds::PLACEMENT_POSITION,
            default => TraitIds::PLACEMENT_DIRECTION,
        };
    }
}
