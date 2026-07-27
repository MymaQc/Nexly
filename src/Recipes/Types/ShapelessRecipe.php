<?php

namespace Nexly\Recipes\Types;

use Attribute;
use pocketmine\crafting\ExactRecipeIngredient;
use pocketmine\crafting\ShapelessRecipe as ShapelessRecipePM;
use pocketmine\crafting\ShapelessRecipeType;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

#[Attribute(Attribute::TARGET_CLASS)]
class ShapelessRecipe implements Recipe
{
    /**
     * @param ShapelessRecipeType $type
     * @param list<Item|string> $ingredients
     * @param list<Item|string> $outputs
     */
    public function __construct(
        private readonly ShapelessRecipeType $type,
        private readonly array               $ingredients,
        private readonly array               $outputs
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
            array_map(fn (Item|string $ingredient): ExactRecipeIngredient => new ExactRecipeIngredient(self::resolveItem($ingredient)), $this->ingredients),
            array_map(fn (Item|string $output): Item => self::resolveItem($output), $this->outputs),
            $this->type
        );
    }

    private static function resolveItem(Item|string $item): Item
    {
        if ($item instanceof Item) {
            return $item;
        }

        return StringToItemParser::getInstance()->parse($item)
            ?? throw new \InvalidArgumentException("Unknown recipe item identifier: $item");
    }
}
