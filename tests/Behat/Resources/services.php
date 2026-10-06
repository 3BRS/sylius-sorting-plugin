<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->defaults()
        ->public();

    $services->set('sylius.behat.context.ui.admin.sorting', \Tests\ThreeBRS\SortingPlugin\Behat\Context\Ui\Admin\ManagingSortingContext::class)
        ->args([
            service(\Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting\SortingPageInterface::class),
            service('sylius.behat.notification_checker.admin'),
            service('sylius.repository.taxon'),
        ]);

    $services->set(\Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting\SortingPageInterface::class, \Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting\SortingPage::class)
        ->public()
        ->parent('sylius.behat.symfony_page');
};
