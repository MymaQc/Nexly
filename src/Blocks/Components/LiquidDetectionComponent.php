<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\LiquidRule;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

#[Attribute(Attribute::TARGET_CLASS)]
class LiquidDetectionComponent extends BlockComponent
{
    /**
     * @param list<LiquidRule> $rules
     */
    public function __construct(
        private readonly array $rules = [new LiquidRule(false)],
    ) {
        if ($rules === []) {
            throw new InvalidArgumentException("Liquid detection requires at least one rule (Bedrock 1.26.10+).");
        }
        $types = [];
        foreach ($rules as $rule) {
            if (!$rule instanceof LiquidRule) {
                throw new InvalidArgumentException("Detection rules must be LiquidRule instances.");
            }
            $type = $rule->getLiquidType()->value;
            if (isset($types[$type])) {
                throw new InvalidArgumentException("Only one detection rule is allowed per liquid type.");
            }
            $types[$type] = true;
        }
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::LIQUID_DETECTION->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        return CompoundTag::create()
            ->setTag("detectionRules", new ListTag(array_map(static fn (LiquidRule $rule): CompoundTag => $rule->toNBT(), $this->rules), NBT::TAG_Compound));
    }
}
