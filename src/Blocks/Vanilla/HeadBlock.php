<?php

namespace Nexly\Blocks\Vanilla;

use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\block\MobHead;
use pocketmine\block\utils\MobHeadType;

class HeadBlock extends MobHead
{
    protected MobHeadType $mobHeadType = MobHeadType::PLAYER;

    public function __construct(
        BlockIdentifier         $idInfo,
        string                  $name,
        private readonly string $texture,
        BlockTypeInfo           $typeInfo
    ) {
        parent::__construct($idInfo, $name, $typeInfo);
    }

    /**
     * @return string
     */
    public function getTexture(): string
    {
        return $this->texture;
    }
}
