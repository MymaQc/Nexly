<?php

declare(strict_types=1);

namespace Nexly\Compatibility;

/**
 * Implemented when a component instance opts into a version-gated field.
 */
interface ComponentFormatFeatureProvider
{
    /**
     * @return list<string> Component or field identifiers whose format support must be checked
     */
    public function getBedrockFormatFeatures(): array;
}
