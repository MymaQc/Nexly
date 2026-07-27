<?php

namespace Nexly\Blocks;

use Closure;
use pocketmine\block\Block;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;

/**
 * @internal
 * @deprecated
 *
 * @phpstan-type AsyncBlockDefinition array{
 *     int,
 *     Closure(int): Block,
 *     string,
 *     string,
 *     string,
 *     string,
 *     Closure(mixed...): BlockStateWriter,
 *     Closure(BlockStateReader): Block
 * }
 */
class AsyncInitialization
{
    /** @var array<string, AsyncBlockDefinition> */
    private static array $blocks = [];

    /**
     * Adds an asynchronous block definition.
     *
     * @param string $stringId
     * @param AsyncBlockDefinition $data
     * @return void
     */
    public static function addAsyncBlock(string $stringId, array $data): void
    {
        self::$blocks[$stringId] = $data;
    }

    /**
     * @return array<string, AsyncBlockDefinition>
     */
    public static function getBlocks(): array
    {
        return self::$blocks;
    }
}
