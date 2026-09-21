<?php

namespace Nexly\Recipes\Types;

use Attribute;
use InvalidArgumentException;
use pocketmine\crafting\ExactRecipeIngredient;
use pocketmine\crafting\ShapelessRecipe as ShapelessRecipePM;
use pocketmine\crafting\ShapelessRecipeType;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class ShapelessRecipe implements Recipe
{
    /**
     * @param ShapelessRecipeType $type
     * @param list<Item|string> $ingredients
     * @param list<Item|string> $outputs
     */
    public function __construct(
        private ShapelessRecipeType $type,
        private array               $ingredients,
        private array               $outputs
    ) {
    }

    /**
     * @return ShapelessRecipeType
     */
    public function getType(): ShapelessRecipeType
    {
        return $this->type;
    }

    /**
     * @return list<Item|string>
     */
    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    /**
     * @return list<Item|string>
     */
    public function getOutputs(): array
    {
        return $this->outputs;
    }

    /**
     * Builds and returns the ShapedRecipePM instance.
     *
     * @return ShapelessRecipePM
     */
    public function build(): ShapelessRecipePM
    {
        return new ShapelessRecipePM(
            array_map(static fn (Item|string $ingredient): ExactRecipeIngredient => new ExactRecipeIngredient(self::resolveItem($ingredient)), $this->ingredients),
            array_map(static fn (Item|string $output): Item => self::resolveItem($output), $this->outputs),
            $this->type
        );
    }

    private static function resolveItem(Item|string $item): Item
    {
        if ($item instanceof Item) {
            return $item;
        }

        return StringToItemParser::getInstance()->parse($item)
            ?? throw new InvalidArgumentException("Unknown recipe item identifier: $item");
    }
}
