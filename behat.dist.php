<?php

declare(strict_types=1);

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\MinkExtension\ServiceContainer\MinkExtension;
use FriendsOfBehat\MinkDebugExtension\ServiceContainer\MinkDebugExtension;
use FriendsOfBehat\SuiteSettingsExtension\ServiceContainer\SuiteSettingsExtension;
use FriendsOfBehat\SymfonyExtension\ServiceContainer\SymfonyExtension;
use FriendsOfBehat\VariadicExtension\ServiceContainer\VariadicExtension;
use Tests\ThreeBRS\SortingPlugin\Kernel;

return (new Config())
    ->import('tests/Behat/Resources/suites.php')
    ->withProfile(
        (new Profile('default'))
            ->withExtension(new Extension(MinkDebugExtension::class, [
                'directory' => 'behat',
                'clean_start' => false,
                'screenshot' => true,
            ]))
            ->withExtension(new Extension(MinkExtension::class, [
                'files_path' => '%paths.base%/vendor/sylius/sylius/src/Sylius/Behat/Resources/fixtures/',
                'base_url' => 'http://localhost:8080/',
                'default_session' => 'symfony',
                'sessions' => [
                    'symfony' => [
                        'symfony' => null,
                    ],
                ],
                'show_auto' => false, // do not automatically open browser on error
            ]))
            ->withExtension(new Extension(SymfonyExtension::class, [
                'bootstrap' => 'tests/Application/config/bootstrap.php',
                'kernel' => [
                    'class' => Kernel::class,
                ],
            ]))
            ->withExtension(new Extension(VariadicExtension::class))
            ->withExtension(new Extension(SuiteSettingsExtension::class, [
                'paths' => ['features'],
            ])),
    );
