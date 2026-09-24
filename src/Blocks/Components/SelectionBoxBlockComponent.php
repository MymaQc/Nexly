<?php

namespace Nexly\Blocks\Components;

use Attribute;
use InvalidArgumentException;
use Nexly\Blocks\Components\Types\BoxCollision;
use pocketmine\block\Beetroot;
use pocketmine\block\Block;
use pocketmine\block\Carrot;
use pocketmine\block\Crops;
use pocketmine\block\NetherWartPlant;
use pocketmine\block\Potato;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;

#[Attribute(Attribute::TARGET_CLASS)]
class SelectionBoxBlockComponent extends BlockComponent
{
    /**
     * @param bool $enabled
     * @param BoxCollision[] $collisions
     */
    public function __construct(
        private readonly bool $enabled,
        private array $collisions = [],
    ) {
        if (\count($collisions) > 1) {
            throw new InvalidArgumentException("Selection supports exactly one box; use collision_box for multiple boxes.");
        }
        foreach ($collisions as $collision) {
            if (!$collision instanceof BoxCollision) {
                throw new InvalidArgumentException("Selection boxes must be BoxCollision instances.");
            }
            if ($collision->getOrigin()->getY() + $collision->getSize()->getY() > 16) {
                throw new InvalidArgumentException("Selection boxes must remain within the 16-unit block height.");
            }
        }
        if (empty($this->collisions)) {
            $this->collisions[] = new BoxCollision(new Vector3(-8.0, 0.0, -8.0), new Vector3(16.0, 16.0, 16.0));
        }
    }

    /**
     * Creates the component from a Block instance.
     *
     * @param int $age
     * @param Crops|NetherWartPlant $block
     * @return self
     */
    public static function fromCrops(Crops|NetherWartPlant $block, int $age): self
    {
        if ($age < 0 || $age > $block::MAX_AGE) {
            throw new InvalidArgumentException("Crop age is outside its supported range.");
        }
        return new self(
            true,
            [
                new BoxCollision(
                    new Vector3(-8.0, 0.0, -8.0),
                    match(true) {
                        $block instanceof Carrot => new Vector3(16.0, (($age + 1.0) / ($block::MAX_AGE + 1)) * 0.7 * 16, 16.0),
                        $block instanceof Potato, $block instanceof Beetroot => new Vector3(16.0, (($age + 1.0) / ($block::MAX_AGE + 1)) * 0.6 * 16, 16.0),
                        $block instanceof NetherWartPlant => new Vector3(16.0, ($age + 1.0) * 0.25 * 16, 16.0),
                        default => new Vector3(16.0, ($age + 1) / ($block::MAX_AGE + 1) * 16, 16.0),
                    }
                )
            ]
        );
    }

    /**
     * Returns the registered Bedrock component identifier.
     *
     * @return string
     */
    public function getName(): string
    {
        return BlockComponentIds::SELECTION_BOX->getValue();
    }

    /**
     * Returns the component in the correct NBT format supported by the client.
     *
     * @return CompoundTag
     */
    public function toNBT(): CompoundTag
    {
        if (!$this->enabled) {
            return CompoundTag::create()->setTag("enabled", new ByteTag(0));
        }

        $collision = $this->collisions[array_key_first($this->collisions)];
        if ($collision->getOrigin()->getY() + $collision->getSize()->getY() > 16) {
            throw new InvalidArgumentException("Selection boxes must remain within the 16-unit block height.");
        }
        return $collision->toNBT(false)->setTag("enabled", new ByteTag(1));
    }
}
