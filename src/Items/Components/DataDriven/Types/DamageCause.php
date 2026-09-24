<?php

namespace Nexly\Items\Components\DataDriven\Types;

enum DamageCause: string
{
    case ALL = "all";
    case FIRE = "fire";
    case LAVA = "lava";
    case DROWNING = "drowning";
    case FALL = "fall";
    case BLOCK_EXPLOSION = "block_explosion";
    case ENTITY_EXPLOSION = "entity_explosion";
    case CAMPFIRE = "campfire";
    case CHARGING = "charging";
    case MAGIC = "magic";
    case WITHER = "wither";
    case STARVE = "starve";
    case SUFFOCATION = "suffocation";
    case VOID = "void";
    case CONTACT = "contact";
    case PROJECTILE = "projectile";
    case ENTITY_ATTACK = "entity_attack";
    case ANVIL = "anvil";
    case FALLING_BLOCK = "falling_block";
    case THORNS = "thorns";
    case FIRE_TICK = "fire_tick";
    case FIREWORKS = "fireworks";
    case FLY_INTO_WALL = "fly_into_wall";
    case FREEZING = "freezing";
    case LIGHTNING = "lightning";
    case MACE_SMASH = "mace_smash";
    case MAGMA = "magma";
    case NONE = "none";
    case OVERRIDE = "override";
    case PISTON = "piston";
    case RAM_ATTACK = "ram_attack";
    case SELF_DESTRUCT = "self_destruct";
    case SONIC_BOOM = "sonic_boom";
    case SOUL_CAMPFIRE = "soul_campfire";
    case STALACTITE = "stalactite";
    case STALAGMITE = "stalagmite";
    case TEMPERATURE = "temperature";

    /**
     * Get the string value of the damage cause.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
