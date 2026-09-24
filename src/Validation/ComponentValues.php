<?php

declare(strict_types=1);

namespace Nexly\Validation;

use InvalidArgumentException;

/**
 * Validation shared by component constructors, before NBT serialization.
 */
final class ComponentValues
{
    public static function customIdentifier(string $value): void
    {
        if (preg_match('~^[a-z0-9_.-]+:[a-z0-9_./-]+$~D', $value) !== 1) {
            throw new InvalidArgumentException("Identifiers must use a lowercase namespace and resource path.");
        }
        if (str_starts_with($value, "minecraft:")) {
            throw new InvalidArgumentException("Custom identifiers cannot use the reserved minecraft namespace.");
        }
    }

    public static function finite(float ...$values): void
    {
        foreach ($values as $value) {
            if (!is_finite($value) || abs($value) > 3.4028234663852886e38) {
                throw new InvalidArgumentException("Component numbers must be finite and representable by an NBT float.");
            }
        }
    }

    /**
     * @param class-string $class
     */
    public static function objects(array $values, string $class): void
    {
        if (!array_is_list($values)) {
            throw new InvalidArgumentException("Component collections must be lists.");
        }
        foreach ($values as $value) {
            if (!$value instanceof $class) {
                throw new InvalidArgumentException("Component entries must be instances of " . $class);
            }
        }
    }

    /**
     * @param array<mixed> $values
     */
    public static function strings(array $values): void
    {
        if (!array_is_list($values)) {
            throw new InvalidArgumentException("Component collections must be lists.");
        }
        foreach ($values as $value) {
            if (!is_string($value) || $value === "") {
                throw new InvalidArgumentException("Component entries must be non-empty strings.");
            }
        }
    }
}
