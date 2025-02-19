<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting\SortingPageInterface;

final class ManagingSortingContext implements Context
{
    public function __construct(
        private SortingPageInterface $sortingPage,
        private NotificationCheckerInterface $notificationChecker,
        private TaxonRepositoryInterface $taxonRepository
    )
    {
    }

    /**
     * @Given I open the :arg1 taxon sorting page
     */
    public function iOpenTheTaxonSortingPage($arg1)
    {
        $taxon = $this->taxonRepository->findOneBy(['code' => $arg1]);
        $this->sortingPage->open(['taxonId' => $taxon->getId()]);
    }

    /**
     * @When I save position
     */
    public function iSavePosition()
    {
        $this->sortingPage->saveSorting();
    }

    /**
     * @Then I should be notified that the products has been sorted
     */
    public function iShouldBeNotifiedThatTheProductsHasBeenSorted()
    {
        $this->notificationChecker->checkNotification(
            'Success, products has been sorted.',
            NotificationType::success()
        );
    }

    /**
     * @Given /^"([^"]*)" product is in 1st position$/
     * @Then I should see the :arg1 in 1st position
     */
    public function iShouldSeeTheInStPosition($arg1)
    {
        $this->sortingPage->getPosition($arg1, 1);
    }

    /**
     * @Then I should see :arg1 disabled
     */
    public function iShouldSeeDisabled($arg1)
    {
        $this->sortingPage->getState($arg1);
    }
}
