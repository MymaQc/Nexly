<?php

declare(strict_types=1);

namespace Nexly\Blocks\Components\Types;

use Nexly\Validation\ComponentValues;

use InvalidArgumentException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;

final readonly class Material
{
    public function __construct(
        private string               $texture,
        private MaterialTarget|string $target = MaterialTarget::ALL,
        private MaterialRenderMethod $renderMethod = MaterialRenderMethod::OPAQUE,
        private TintMethod|string    $tintMethod = TintMethod::NONE,
        private float                $ambientOcclusion = 1.0,
        private bool                 $faceDimming = true,
        private bool                 $isotropic = false,
        private bool                 $packedBools = false,
        private bool                 $alphaMaskedTint = false,
    ) {
        ComponentValues::finite($ambientOcclusion);
        if (!is_finite($ambientOcclusion) || $ambientOcclusion < 0.0 || $ambientOcclusion > 10.0) {
            throw new InvalidArgumentException("Ambient occlusion must be between 0.0 and 10.0.");
        }
        if (is_string($tintMethod) && TintMethod::tryFrom($tintMethod) === null) {
            throw new InvalidArgumentException("Unknown biome tint method.");
        }
        if ($target === "") {
            throw new InvalidArgumentException("Material instance name cannot be empty.");
        }
        if ($texture === "") {
            throw new InvalidArgumentException("Material texture identifier cannot be empty.");
        }
        if ($alphaMaskedTint && ($this->tintMethod === TintMethod::NONE || $this->renderMethod !== MaterialRenderMethod::OPAQUE)) {
            throw new InvalidArgumentException("Alpha-masked tint requires a non-none tint method and opaque rendering.");
        }
    }

    /**
     * @return MaterialTarget|string
     */
    public function getTarget(): MaterialTarget|string
    {
        return $this->target;
    }

    public function getTargetName(): string
    {
        return $this->target instanceof MaterialTarget ? $this->target->value : $this->target;
    }

    public function getRenderMethod(): MaterialRenderMethod
    {
        return $this->renderMethod;
    }

    /**
     * Returns the material in the correct NBT format supported by the client.
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("ambient_occlusion", new FloatTag($this->ambientOcclusion))
            ->setTag("packed_bools", new ByteTag($this->packedBools ? 1 : 0))
            ->setTag("render_method", new StringTag($this->renderMethod->getValue()))
            ->setTag("texture", new StringTag($this->texture))
            ->setTag("alpha_masked_tint", new ByteTag($this->alphaMaskedTint ? 1 : 0))
            ->setTag("face_dimming", new ByteTag($this->faceDimming ? 1 : 0))
            ->setTag("isotropic", new ByteTag($this->isotropic ? 1 : 0))
            ->setTag("tint_method", new StringTag($this->tintMethod instanceof TintMethod ? $this->tintMethod->value : $this->tintMethod));
    }
}
