<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum ItemCooldownAction: string
{
    case USE = "use";
    case ATTACK = "attack";

    public function getValue(): string
    {
        return $this->value;
    }
}
