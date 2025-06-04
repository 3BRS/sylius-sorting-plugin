<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;


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

   public function getPosition($expectedProductName, int $position): void
{
    $page = $this->getSession()->getPage();
    $element = $page->find('css', '#sortableProducts .sortableItem:nth-child(' . $position . ') .content .header');

    Assert::notNull(
        $element,
        sprintf(
            'Could not find product element at position %d using selector: "#sortableProducts .sortableItem:nth-child(%d) .content .header"',
            $position,
            $position
        )
    );

    $actualText = trim($element->getText());
    Assert::same(
        $actualText,
        $expectedProductName,
        sprintf('Expected "%s" in position %d but found "%s".', $expectedProductName, $position, $actualText)
    );
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
