<?php

namespace Nexly\Blocks\Components\Types;

use InvalidArgumentException;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

readonly class LiquidRule
{
    /**
     * @param list<BlockFace|string> $stopsLiquidFlowingFromDirection
     */
    public function __construct(
        private bool              $canContainLiquid,
        private LiquidType        $liquidType = LiquidType::WATER,
        private LiquidTouchAction $onLiquidTouches = LiquidTouchAction::BLOCKING,
        private array             $stopsLiquidFlowingFromDirection = [],
        private bool              $useLiquidClipping = false,
    ) {
        foreach ($stopsLiquidFlowingFromDirection as $direction) {
            if (!$direction instanceof BlockFace && (!is_string($direction) || BlockFace::tryFrom($direction) === null)) {
                throw new InvalidArgumentException("Invalid stopped liquid-flow direction.");
            }
        }
    }

    /**
     * @return bool
     */
    public function canContainLiquid(): bool
    {
        return $this->canContainLiquid;
    }

    /**
     * @return LiquidType
     */
    public function getLiquidType(): LiquidType
    {
        return $this->liquidType;
    }

    /**
     * @return LiquidTouchAction
     */
    public function onLiquidTouches(): LiquidTouchAction
    {
        return $this->onLiquidTouches;
    }

    /**
     * @return list<BlockFace|string>
     */
    public function stopsLiquidFromDirection(): array
    {
        return $this->stopsLiquidFlowingFromDirection;
    }

    /**
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("canContainLiquid", new ByteTag($this->canContainLiquid ? 1 : 0))
            ->setTag("liquidType", new StringTag($this->liquidType->getValue()))
            ->setTag("onLiquidTouches", new StringTag($this->onLiquidTouches->getValue()))
            ->setTag("stopsLiquidFromDirection", new ListTag(array_values(array_map(static fn (BlockFace|string $direction): StringTag => new StringTag($direction instanceof BlockFace ? $direction->value : $direction), $this->stopsLiquidFlowingFromDirection)), NBT::TAG_String))
            ->setTag("useLiquidClipping", new ByteTag($this->useLiquidClipping ? 1 : 0));
    }
}
