<?php

namespace Nexly\Items\Components\DataDriven\Types;

/**
 * Bedrock built-in particle type values, documentation snapshot 2026-09-23.
 * Availability depends on the client version.
 * @see https://learn.microsoft.com/en-us/minecraft/creator/reference/content/itemreference/examples/itemcomponents/minecraft_durability_sensor
 */
enum ParticleType: string
{
    case BALLOONGAS = "balloongas";
    case BLEACH = "bleach";
    case BLOCKFORCEFIELD = "blockforcefield";
    case BLUEFLAME = "blueflame";
    case BREEZEWINDEXPLOSION = "breezewindexplosion";
    case BUBBLE = "bubble";
    case BUBBLECOLUMNDOWN = "bubblecolumndown";
    case BUBBLECOLUMNUP = "bubblecolumnup";
    case BUBBLEMANUAL = "bubblemanual";
    case CAMPFIRESMOKE = "campfiresmoke";
    case CAMPFIRESMOKETALL = "campfiresmoketall";
    case CANDLEFLAME = "candleflame";
    case CARROTBOOST = "carrotboost";
    case COLOREDFLAME = "coloredflame";
    case CONDUIT = "conduit";
    case CREAKINGCRUMBLE = "creakingcrumble";
    case CRIT = "crit";
    case DRAGONBREATH = "dragonbreath";
    case DRAGONBREATHFIRE = "dragonbreathfire";
    case DRAGONBREATHTRAIL = "dragonbreathtrail";
    case DRAGONDESTROYBLOCK = "dragondestroyblock";
    case DRIPHONEY = "driphoney";
    case DRIPLAVA = "driplava";
    case DRIPWATER = "dripwater";
    case DUSTPLUME = "dustplume";
    case ELECTRICSPARK = "electricspark";
    case ENCHANTINGTABLE = "enchantingtable";
    case ENDROD = "endrod";
    case EVAPORATION = "evaporation";
    case EXPLODE = "explode";
    case EYEBLOSSOMCLOSE = "eyeblossomclose";
    case EYEBLOSSOMOPEN = "eyeblossomopen";
    case FALLINGBORDERDUST = "fallingborderdust";
    case FALLINGDUST = "fallingdust";
    case FIREWORKS = "fireworks";
    case FIREWORKSOVERLAY = "fireworksoverlay";
    case FIREWORKSSTARTER = "fireworksstarter";
    case FLAME = "flame";
    case FOOD = "food";
    case GREENFLAME = "greenflame";
    case HEART = "heart";
    case HUGEEXPLOSION = "hugeexplosion";
    case ICONCRACK = "iconcrack";
    case INK = "ink";
    case LARGEEXPLODE = "largeexplode";
    case LARGESMOKE = "largesmoke";
    case LAVA = "lava";
    case MOBAPPEARANCE = "mobappearance";
    case MOBFLAME = "mobflame";
    case MOBSPELL = "mobspell";
    case MOBSPELLAMBIENT = "mobspellambient";
    case MOBSPELLINSTANTANEOUS = "mobspellinstantaneous";
    case MYCELIUMDUST = "myceliumdust";
    case NONE = "none";
    case NOTE = "note";
    case OBSIDIANTEAR = "obsidiantear";
    case ORANGEPOPLARLEAVES = "orangepoplarleaves";
    case PALEOAKLEAVES = "paleoakleaves";
    case PAUSEMOBGROWTH = "pausemobgrowth";
    case PORTAL = "portal";
    case PORTALREVERSE = "portalreverse";
    case RAINSPLASH = "rainsplash";
    case REDDUST = "reddust";
    case REDPOPLARLEAVES = "redpoplarleaves";
    case RESETMOBGROWTH = "resetmobgrowth";
    case RISINGBORDERDUST = "risingborderdust";
    case SCULKSOUL = "sculksoul";
    case SHRIEK = "shriek";
    case SHULKERBULLET = "shulkerbullet";
    case SLIME = "slime";
    case SMOKE = "smoke";
    case SNEEZE = "sneeze";
    case SNOWBALLPOOF = "snowballpoof";
    case SNOWFLAKE = "snowflake";
    case SONICEXPLOSION = "sonicexplosion";
    case SOUL = "soul";
    case SPARKLER = "sparkler";
    case SPIT = "spit";
    case STALACTITEDRIPLAVA = "stalactitedriplava";
    case STALACTITEDRIPWATER = "stalactitedripwater";
    case SULFURCUBE = "sulfurcube";
    case TERRAIN = "terrain";
    case TOTEM = "totem";
    case TOWNAURA = "townaura";
    case TRACKINGEMITTER = "trackingemitter";
    case VAULTCONNECTION = "vaultconnection";
    case VILLAGERANGRY = "villagerangry";
    case VILLAGERHAPPY = "villagerhappy";
    case WATERSPLASH = "watersplash";
    case WATERSPLASHMANUAL = "watersplashmanual";
    case WATERWAKE = "waterwake";
    case WAX = "wax";
    case WHITESMOKE = "whitesmoke";
    case WINDEXPLOSION = "windexplosion";
    case WITCHSPELL = "witchspell";
    case WOLFARMORCRACK = "wolfarmorcrack";
    case YELLOWPOPLARLEAVES = "yellowpoplarleaves";

    public function getValue(): string
    {
        return $this->value;
    }
}
