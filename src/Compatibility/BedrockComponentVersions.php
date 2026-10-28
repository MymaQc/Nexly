<?php

declare(strict_types=1);

namespace Nexly\Compatibility;

use InvalidArgumentException;

/**
 * Snapshot of minimum behavior-pack format versions explicitly stated by Learn or Bedrock Wiki.
 */
final class BedrockComponentVersions
{
    /**
     * @var array<string, string>
     */
    private const array MINIMUM_FORMATS = [
        'minecraft:crafting_table' => '1.19.50',
        'minecraft:destructible_by_explosion' => '1.19.20',
        'minecraft:destructible_by_mining' => '1.19.20',
        'minecraft:light_dampening' => '1.19.10',
        'minecraft:collision_box' => '1.19.50',
        'minecraft:display_name' => '1.19.60',
        'minecraft:friction' => '1.19.20',
        'minecraft:light_emission' => '1.19.20',
        'minecraft:selection_box' => '1.19.60',
        'minecraft:transformation' => '1.21.0',
        'minecraft:entity_fall_on' => '1.21.10',
        'minecraft:tick' => '1.21.10',
        'minecraft:item_visual' => '1.21.60',
        'minecraft:replaceable' => '1.21.70',
        'minecraft:destruction_particles' => '1.21.90',
        'minecraft:geometry' => '1.21.90',
        'minecraft:precipitation_interactions' => '1.21.120',
        'minecraft:random_offset' => '1.21.100',
        'minecraft:redstone_producer' => '1.21.120',
        'minecraft:connection_rule' => '1.26.0',
        'minecraft:liquid_detection' => '1.26.0',
        'minecraft:support' => '1.26.0',
        'minecraft:leashable' => '1.26.0',
        'minecraft:block_entity' => '1.26.20',
        'minecraft:chest_obstruction' => '1.26.20',
        'minecraft:flower_pottable' => '1.26.20',
        'minecraft:material_instances' => '1.26.20',
        'minecraft:tags' => '1.26.20',
        'minecraft:sound' => '1.26.20',
        'minecraft:map_color.tint_method' => '1.21.80',
        'minecraft:redstone_conductivity' => '1.21.40',
        'minecraft:movable' => '1.21.100',
        'minecraft:redstone_consumer' => '1.21.130',
        'minecraft:placement_filter' => '1.26.20',
        'minecraft:instrument_sound' => '1.26.40',
        'minecraft:block_entity.container.title' => '1.26.60',
        'minecraft:block_entity.entity_storage' => '1.26.60',
        'minecraft:enchantment_power_transmitter' => '1.26.60',
        'minecraft:neighbor_change' => '1.26.60',
        'minecraft:vibration_properties' => '1.26.60',
    ];

    public static function minimumFormatVersion(string $feature): ?string
    {
        return self::MINIMUM_FORMATS[$feature] ?? null;
    }

    /**
     * @param list<string> $features Component IDs or documented feature keys
     * @return array<string, string> Unsupported feature => required format version
     */
    public static function unsupportedFeatures(array $features, string $targetFormatVersion): array
    {
        self::versionParts($targetFormatVersion);
        if (!array_is_list($features)) {
            throw new InvalidArgumentException('Feature names must be provided as a list.');
        }
        foreach ($features as $feature) {
            if (!\is_string($feature)) {
                throw new InvalidArgumentException('Feature names must be strings.');
            }
        }
        $result = [];
        foreach (array_unique($features) as $feature) {
            if (!\is_string($feature)) {
                throw new InvalidArgumentException('Feature names must be strings.');
            }
            $minimum = self::minimumFormatVersion($feature);
            if ($minimum !== null && version_compare($minimum, $targetFormatVersion, '>')) {
                $result[$feature] = $minimum;
            }
        }
        return $result;
    }

    /**
     * @param string $version
     * @return void
     */
    private static function versionParts(string $version): void {
        if (preg_match('/^(\d+)\.(\d+)(?:\.(\d+))?$/D', $version) !== 1) {
            throw new InvalidArgumentException('Format version must look like 1.21.80.');
        }
    }
}
