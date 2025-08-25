<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

$config = RectorConfig::configure();

$config
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkipPath(__DIR__ . '/tests/Application/var')
    ->withSets([
        LevelSetList::UP_TO_PHP_82,
    ])->withPreparedSets(
        deadCode: true,
        doctrineCodeQuality: true,
        symfonyCodeQuality: true,
    )->withComposerBased(
        twig: true,
        doctrine: true,
        phpunit: true,
        symfony: true,
    );

$config->withImportNames();

return $config;
