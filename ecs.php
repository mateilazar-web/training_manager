<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([__DIR__ . '/app']);

    $ecsConfig->sets([
        SetList::PSR_12, // Use PSR-12 coding standard
        SetList::CLEAN_CODE, // Improve code readability
    ]);
};
