<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting\SortingIndexPageInterface;
use Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting\SortingPageInterface;
use Webmozart\Assert\Assert;

final readonly class ManagingSortingContext implements Context
{
    public function __construct(
        private SortingPageInterface $sortingPage,
        private SortingIndexPageInterface $sortingIndexPage,
        private NotificationCheckerInterface $notificationChecker,
        private TaxonRepositoryInterface $taxonRepository,
    ) {
    }

    #[When('I open the sorting page')]
    public function iOpenTheSortingPage(): void
    {
        $this->sortingIndexPage->open();
    }

    #[Then('the taxon tree should use the Sylius admin taxon tree controller')]
    public function theTaxonTreeShouldUseTheSyliusAdminTaxonTreeController(): void
    {
        Assert::true(
            $this->sortingIndexPage->usesSyliusTaxonTreeController(),
            'The taxon tree does not use the "@sylius/admin-bundle/taxon-tree" Stimulus controller.',
        );
    }

    #[Then('the taxon tree should contain the :taxonName taxon')]
    public function theTaxonTreeShouldContainTheTaxon(string $taxonName): void
    {
        Assert::inArray($taxonName, $this->sortingIndexPage->getTaxonTreeNames());
    }

    #[Given('I open the :arg1 taxon sorting page')]
    public function iOpenTheTaxonSortingPage($arg1)
    {
        $taxon = $this->taxonRepository->findOneBy(['code' => $arg1]);
        $this->sortingPage->open(['taxonId' => $taxon->getId()]);
    }

    #[When('I save position')]
    public function iSavePosition()
    {
        $this->sortingPage->saveSorting();
    }

    #[Then('I should be notified that the products has been sorted')]
    public function iShouldBeNotifiedThatTheProductsHasBeenSorted()
    {
        $this->notificationChecker->checkNotification(
            'Success, products has been sorted.',
            NotificationType::success(),
        );
    }

    #[Given('/^"([^"]*)" product is in 1st position$/')]
    #[Then('I should see the :arg1 in 1st position')]
    public function iShouldSeeTheInStPosition($arg1)
    {
        $this->sortingPage->checkProductOnPosition($arg1, 1);
    }

    #[Then('I should see :arg1 disabled')]
    public function iShouldSeeDisabled($arg1)
    {
        $this->sortingPage->getState($arg1);
    }
}
