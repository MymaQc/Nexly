<?php

namespace Nexly\Recipes\Types;

use Attribute;
use Nexly\Recipes\MinecraftShape;
use pocketmine\crafting\ExactRecipeIngredient;
use pocketmine\crafting\ShapedRecipe as ShapedRecipePM;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;

#[Attribute(Attribute::TARGET_CLASS)]
class ShapedRecipe implements Recipe
{
    /** @var non-empty-list<string> */
    private array $shape;

    /**
     * @param MinecraftShape|list<string> $shape
     * @param array<string, Item|string> $ingredients
     * @param list<Item|string> $outputs
     */
    public function __construct(
        MinecraftShape|array $shape,
        private readonly array       $ingredients,
        private readonly array       $outputs
    ) {
        $resolvedShape = $shape instanceof MinecraftShape ? $shape->toArray() : $shape;
        if ($resolvedShape === []) {
            throw new \InvalidArgumentException("A shaped recipe must contain at least one row.");
        }

        $this->shape = $resolvedShape;
    }

    /**
     * @return non-empty-list<string>
     */
    public function getShape(): array
    {
        return $this->shape;
    }

    /**
     * @return array<string, Item|string>
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
     * @return ShapedRecipePM
     */
    public function build(): ShapedRecipePM
    {
        return new ShapedRecipePM(
            $this->shape,
            array_map(fn (Item|string $ingredient): ExactRecipeIngredient => new ExactRecipeIngredient(self::resolveItem($ingredient)), $this->ingredients),
            array_map(fn (Item|string $output): Item => self::resolveItem($output), $this->outputs),
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
