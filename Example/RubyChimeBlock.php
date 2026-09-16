<?php

namespace Nexly\Exemple;

use Nexly\Blocks\Components\BlockEntityBlockComponent;
use Nexly\Blocks\Components\ChestObstructionBlockComponent;
use Nexly\Blocks\Components\InstrumentSoundBlockComponent;
use Nexly\Blocks\Components\SoundBlockComponent;
use Nexly\Blocks\Components\Types\ChestObstructionRule;
use Nexly\Blocks\Components\Types\NoteInstrument;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockTypeInfo;
use pocketmine\item\ToolTier;

#[SoundBlockComponent("glass")]
#[BlockEntityBlockComponent(dynamicProperties: true, containerSlotCount: 27)]
#[InstrumentSoundBlockComponent(up: NoteInstrument::BASS_ATTACK, down: NoteInstrument::BIT)]
#[ChestObstructionBlockComponent(ChestObstructionRule::ALWAYS)]
final class RubyChimeBlock extends Block
{

    public function __construct(BlockIdentifier $idInfo)
    {
        parent::__construct($idInfo, "Ruby Chime Block", new BlockTypeInfo(BlockBreakInfo::pickaxe(2.0, ToolTier::IRON, 30.0)));
    }

}