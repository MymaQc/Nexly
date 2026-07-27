<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum ItemStartUsing: string
{
    case ALWAYS = "always";
    case IF_FIRST = "if_first";

    public function getValue(): string
    {
        return $this->value;
    }
}
