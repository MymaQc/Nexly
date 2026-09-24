<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Validation\ComponentValues;
use Nexly\Blocks\Components\Types\NWayVisualRotation;
use Nexly\Blocks\Components\Types\MinecraftGeometry;
use Nexly\Blocks\Components\Types\CullingLayer;
use Nexly\Blocks\Components\Types\CullingShape;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

#[Attribute(Attribute::TARGET_CLASS)]
class GeometryBlockComponent extends BlockComponent
{
    /**
     * @param bool|list<string> $uvLock
     */
    public function __construct(
        private readonly MinecraftGeometry|string $identifier = MinecraftGeometry::FULL_BLOCK,
        private readonly string $culling = "",
        private readonly CullingLayer|string $cullingLayer = CullingLayer::UNDEFINED,
        private readonly CullingShape|string $cullingShape = CullingShape::EMPTY,
        private readonly string|NWayVisualRotation|null $nWayVisualRotation = null,
        private readonly bool|array $uvLock = false,
        private readonly bool $ignoreGeometryForIsSolid = true,
        private readonly bool $needsLegacyTopRotation = false,
        private readonly bool $useBlockTypeLightAbsorption = false,
        private ?CompoundTag $boneVisibility = null
    ) {
        $this->boneVisibility ??= CompoundTag::create();
        $identifierName = $identifier instanceof MinecraftGeometry ? $identifier->value : $identifier;
        if ($identifierName === "" || \strlen($identifierName) > 256 || \strlen($culling) > 256) {
            throw new InvalidArgumentException("Geometry identifier must contain 1..256 bytes and culling at most 256 bytes.");
        }
        self::validateCullingIdentifier($cullingLayer instanceof CullingLayer ? $cullingLayer->value : $cullingLayer, [
            CullingLayer::UNDEFINED->value,
            CullingLayer::LEAVES->value,
        ], "culling layer");
        self::validateCullingIdentifier($cullingShape instanceof CullingShape ? $cullingShape->value : $cullingShape, [
            CullingShape::EMPTY->value,
            CullingShape::UNIT_CUBE->value,
        ], "culling shape");
        if (\is_array($uvLock)) {
            ComponentValues::strings($uvLock);
        }
        if (\is_array($uvLock) && \count($uvLock) > 64) {
            throw new InvalidArgumentException("UV lock cannot contain more than 64 bone names.");
        }
        $this->validateBoneVisibility();
    }

    /**
     * The network component name for block geometry.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::GEOMETRY->getValue();
    }

    /**
     * @param string $boneName
     * @param bool|string $visibility
     * @return $this
     */
    public function add(string $boneName, bool|string $visibility): self
    {
        if ($boneName === "" || $visibility === "") {
            throw new InvalidArgumentException("Bone names and visibility expressions must not be empty.");
        }
        if ($this->boneVisibility->getTag($boneName) === null && \count($this->boneVisibility) >= 64) {
            throw new InvalidArgumentException("Bone visibility cannot contain more than 64 bones.");
        }
        $this->boneVisibility->setTag(
            $boneName,
            \is_bool($visibility) ?
            new ByteTag($visibility ? 1 : 0) : CompoundTag::create()
                ->setString("expression", $visibility)
                ->setShort("version", 12)
        );
        return $this;
    }

    /**
     * @param list<string> $vanillaValues
     */
    private static function validateCullingIdentifier(string $value, array $vanillaValues, string $label): void
    {
        if (\strlen($value) > 256) {
            throw new InvalidArgumentException(ucfirst($label) . " identifier cannot exceed 256 bytes.");
        }
        if (\in_array($value, $vanillaValues, true)) {
            return;
        }
        if (str_starts_with($value, "minecraft:") || \strlen($value) < 2 || preg_match('/^[a-z0-9].*[a-z0-9]$/D', $value) !== 1) {
            throw new InvalidArgumentException("Invalid $label identifier; use a supported vanilla value or a custom name beginning and ending with a lowercase letter or digit.");
        }
    }

    private function validateBoneVisibility(): void
    {
        if (\count($this->boneVisibility) > 64) {
            throw new InvalidArgumentException("Bone visibility cannot contain more than 64 bones.");
        }
        foreach ($this->boneVisibility as $name => $visibility) {
            if ($name === "" || !($visibility instanceof ByteTag || $visibility instanceof CompoundTag)) {
                throw new InvalidArgumentException("Bone visibility requires named boolean or Molang NBT values.");
            }
            if ($visibility instanceof ByteTag && !\in_array($visibility->getValue(), [0, 1], true)) {
                throw new InvalidArgumentException("Bone visibility booleans must be 0 or 1.");
            }
            if ($visibility instanceof CompoundTag && (!($visibility->getTag("expression") instanceof StringTag) || $visibility->getString("expression") === "")) {
                throw new InvalidArgumentException("Bone visibility Molang values require a non-empty expression.");
            }
        }
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        $this->validateBoneVisibility();
        $nbt = CompoundTag::create()
            ->setTag("bone_visibility", $this->boneVisibility)
            ->setTag("identifier", new StringTag($this->identifier instanceof MinecraftGeometry ? $this->identifier->value : $this->identifier))
            ->setTag("culling", new StringTag($this->culling))
            ->setTag("culling_layer", new StringTag($this->cullingLayer instanceof CullingLayer ? $this->cullingLayer->value : $this->cullingLayer))
            ->setTag("ignoreGeometryForIsSolid", new ByteTag($this->ignoreGeometryForIsSolid ? 1 : 0))
            ->setTag("needsLegacyTopRotation", new ByteTag($this->needsLegacyTopRotation ? 1 : 0))
            ->setTag("useBlockTypeLightAbsorption", new ByteTag($this->useBlockTypeLightAbsorption ? 1 : 0));

        $nbt->setTag("culling_shape", new StringTag($this->cullingShape instanceof CullingShape ? $this->cullingShape->value : $this->cullingShape));

        if ($this->nWayVisualRotation !== null) {
            $nbt->setTag("n_way_visual_rotation", $this->nWayVisualRotation instanceof NWayVisualRotation ? $this->nWayVisualRotation->toNBT() : new StringTag($this->nWayVisualRotation));
        }

        $nbt->setTag("uv_lock", \is_bool($this->uvLock)
            ? new ByteTag($this->uvLock ? 1 : 0)
            : new ListTag(array_map(static fn (string $bone): StringTag => new StringTag($bone), $this->uvLock), NBT::TAG_String)
        );

        return $nbt;
    }

    public function hasNWayVisualRotation(): bool
    {
        return $this->nWayVisualRotation !== null;
    }
}
