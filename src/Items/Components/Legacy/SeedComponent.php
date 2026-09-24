<?php

namespace Nexly\Items\Components\Legacy;

use Attribute;
use InvalidArgumentException;
use Nexly\Validation\ComponentValues;
use Nexly\Items\Components\Legacy\Types\LegacyFace;
use pocketmine\block\Block;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\world\format\io\GlobalBlockStateHandlers;

#[Attribute(Attribute::TARGET_CLASS)]
class SeedComponent extends LegacyItemComponent
{
    /**
     * The name of the component.
     *
     * @return string
     */
    public static function getName(): string
    {
        return LegacyComponentIds::SEED->getValue();
    }

    /**
     * Create a SeedComponent from blocks.
     *
     * @param Block $result
     * @param Block ...$blocks
     * @return self
     */
    public static function fromBlocks(Block $result, Block ...$blocks): self
    {
        return new self(
            GlobalBlockStateHandlers::getSerializer()->serialize($result->getStateId())->getName(),
            array_values(array_map(
                static fn (Block $block): string => GlobalBlockStateHandlers::getSerializer()->serialize($block->getStateId())->getName(),
                $blocks
            )),
        );
    }

    /**
     * @param list<string> $plantAt
     */
    public function __construct(
        private string $result,
        private array $plantAt = [],
        private readonly LegacyFace $plantFace = LegacyFace::UP,
    ) {
        if ($result === "") {
            throw new InvalidArgumentException("Seed result cannot be empty.");
        }
        ComponentValues::strings($plantAt);
        $this->result = self::legacyIdentifier($result, true);
        $this->plantAt = array_map(static fn (string $block): string => self::legacyIdentifier($block, false), $plantAt);
    }

    private static function legacyIdentifier(string $identifier, bool $crop): string
    {
        // Legacy aliases apply only to whole vanilla identifiers, never to custom names.
        if (str_contains($identifier, ":") && !str_starts_with($identifier, "minecraft:")) {
            return $identifier;
        }
        $name = strtolower(str_starts_with($identifier, "minecraft:") ? substr($identifier, 10) : $identifier);
        return match ($name) {
            "grass_block" => "grass",
            "air" => $crop ? "light_block" : "air",
            default => $name,
        };
    }

    /**
     * Get the maximum damage value.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("crop_result", new StringTag($this->result))
            ->setTag("plant_at_face", new StringTag($this->plantFace->getValue()))
            ->setTag("plant_at_any_solid_surface", new ByteTag(empty($this->plantAt) ? 1 : 0))
            ->setTag("plant_at", new ListTag(array_map(static fn (string $block): StringTag => new StringTag($block), $this->plantAt), NBT::TAG_String));
    }
}
