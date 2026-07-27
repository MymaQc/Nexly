<?php

namespace Nexly\Recipes;

use Nexly\Recipes\Types\Recipe;
use Nexly\Recipes\Types\ShapedRecipe;
use Nexly\Recipes\Types\ShapelessRecipe;
use pocketmine\crafting\ShapedRecipe as ShapedRecipePM;
use pocketmine\crafting\ShapelessRecipe as ShapelessRecipePM;
use pocketmine\crafting\ShapelessRecipeType;
use pocketmine\item\StringToItemParser;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class NexlyRecipes
{
    use SingletonTrait;

    /** @var list<\Closure(): Recipe> */
    private array $recipes = [];

    public function __construct()
    {
        self::setInstance($this);
    }

    /**
     * @return list<\Closure(): Recipe>
     */
    public function getRecipes(): array
    {
        return $this->recipes;
    }

    /**
     * @param list<\Closure(): Recipe> $recipes
     */
    public function setRecipes(array $recipes): void
    {
        $this->recipes = $recipes;
    }

    /**
     * @param \Closure(): Recipe $recipe
     */
    public function addRecipe(\Closure $recipe): void
    {
        $this->recipes[] = $recipe;
    }

    /**
     * Loads a recipe from an associative array.
     *
     * @param array<string, mixed> $data
     * @return void
     */
    public function fromArray(array $data): void
    {
        if (!isset($data["output"])) {
            throw new \InvalidArgumentException("Missing 'output' key");
        }
        if (!isset($data["ingredients"])) {
            throw new \InvalidArgumentException("Missing 'ingredients' key");
        }

        $this->addRecipe(function () use ($data): Recipe {
            $output = $data["output"];
            if (is_string($output)) {
                $output = StringToItemParser::getInstance()->parse($output);
            } elseif (is_array($output)) {
                if (!isset($output["item"])) {
                    throw new \InvalidArgumentException("Invalid 'output' format, missing 'item' key");
                }

                if (isset($output["count"])) {
                    $count = (int)$output["count"];
                    if ($count < 1 || $count > 64) {
                        throw new \InvalidArgumentException("Invalid 'output' count, must be between 1 and 64");
                    }
                    $item = StringToItemParser::getInstance()->parse($output["item"]);
                    $item->setCount($count);
                    $output = $item;
                } else {
                    $output = StringToItemParser::getInstance()->parse($output["item"]);
                }
            } else {
                throw new \InvalidArgumentException("Invalid 'output' format");
            }

            if (count($data["ingredients"]) > 9) {
                throw new \InvalidArgumentException("Too many 'ingredients', maximum is 9");
            }
            if (!isset($data["shape"])) {
                if (!isset($data["type"])) {
                    $type = ShapelessRecipeType::CRAFTING();
                } else {
                    $type = match(strtolower($data["type"])) {
                        "crafting_table" => ShapelessRecipeType::CRAFTING(),
                        "cartography" => ShapelessRecipeType::CARTOGRAPHY(),
                        "smithing" => ShapelessRecipeType::SMITHING(),
                        "stonecutter" => ShapelessRecipeType::STONECUTTER(),
                        default => throw new \InvalidArgumentException("Invalid 'type' value"),
                    };
                }

                return new ShapelessRecipe($type, $data["ingredients"], [$output]);
            }

            return new ShapedRecipe($data["shape"], $data["ingredients"], [$output]);
        });
    }

    /**
     * Loads recipes from a JSON array.
     *
     * @param list<array<string, mixed>> $data
     * @return void
     */
    public function fromJson(array $data): void
    {
        foreach ($data as $recipe) {
            $this->fromArray($recipe);
        }
    }

    /**
     * @return void
     */
    public function registers(): void
    {
        $server = Server::getInstance();
        $craftingManager = $server->getCraftingManager();
        foreach ($this->recipes as $closure) {
            try {
                $recipe = ($closure)();
            } catch (\Exception $e) {
                Server::getInstance()->getLogger()->error("Error while building a recipe: " . $e->getMessage());
                continue;
            }

            $craftingRecipe = $recipe->build();
            if ($craftingRecipe instanceof ShapelessRecipePM) {
                $craftingManager->registerShapelessRecipe($craftingRecipe);
            } elseif ($craftingRecipe instanceof ShapedRecipePM) {
                $craftingManager->registerShapedRecipe($craftingRecipe);
            } else {
                throw new \UnexpectedValueException("Unsupported crafting recipe type: " . $craftingRecipe::class);
            }
        }
    }
}
