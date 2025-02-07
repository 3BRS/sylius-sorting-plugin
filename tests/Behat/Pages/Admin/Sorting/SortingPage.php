<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class SortingPage extends SymfonyPage implements SortingPageInterface
{
    public function getRouteName(): string
    {
        return 'threebrs_admin_sorting_products';
    }

    public function saveSorting(): void
    {
        $this->getElement('save_sorting')->click();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'save_sorting' => 'button:contains("Save positions")',
        ]);
    }

    public function getPosition($arg1, int $int): void
    {
        $Page = $this->getSession()->getPage();
        $firstE = $Page->find('css', '#sortableProducts .sortableItem:nth-child(' . $int . ') .content .header')->getText();
        if ($firstE != $arg1) {
            throw new \RuntimeException(sprintf($firstE));
        }
    }

    public function getState($arg1): void
    {
        $Page = $this->getSession()->getPage();
        $elementA = $Page->find('css', '#sortableProducts .sortableItem:nth-child(1)')->getHtml();
        if (!str_contains($elementA, 'style="opacity: 0.5')) {
            throw new \RuntimeException(sprintf($elementA));
        }
    }
}
