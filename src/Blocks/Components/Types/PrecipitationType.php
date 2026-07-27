<?php

namespace Nexly\Blocks\Components\Types;

enum PrecipitationType: string
{
    case OBSTRUCT_RAIN = "obstruct_rain";
    case OBSTRUCT_RAIN_ACCUMULATE_SNOW = "obstruct_rain_accumulate_snow";
    case SNOWLOGGING = "snowlogging";
    case NONE = "none";

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
