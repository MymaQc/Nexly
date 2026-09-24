<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum ItemStartUsing: string
{
    case ALWAYS = "always";
    case IF_FIRST = "if_first";
    case ON_ATTACK = "on_attack";

    public function getValue(): string
    {
        return $this->value;
    }
}
