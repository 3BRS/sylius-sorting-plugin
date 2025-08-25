<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use RuntimeException;
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

    public function checkProductOnPosition(
            $expectedProductName,
        int $onPosition,
    ): void {
        $page = $this->getSession()->getPage();
        $element = $page->find('css', '#sortableProducts .sortableItem:nth-child(' . $onPosition . ') .card-title');

        Assert::notNull(
            $element,
            sprintf(
                'Could not find product element at position %d using selector: "#sortableProducts .sortableItem:nth-child(%d) .content .header"',
                $onPosition,
                $onPosition
            )
        );
        \assert($element instanceof NodeElement);

        $currentText = trim($element->getText());
        Assert::same(
            $currentText,
            $expectedProductName,
            sprintf('Expected "%s" in position %d but found "%s".', $expectedProductName, $onPosition, $currentText)
        );
    }

    public function getState($arg1): void
    {
        $Page = $this->getSession()->getPage();
        $sortableItems = $Page->findAll('css', '#sortableProducts .sortableItem');

        foreach ($sortableItems as $item) {
            $title = $item->find('css', '.card-title');
            if ($title && $title->getText() === $arg1) {
                $cardBody = $item->find('css', '.card-body');
                if ($cardBody && str_contains((string) $cardBody->getAttribute('style'), 'opacity: 0.5')) {
                    return; // Found the disabled product
                }

                throw new RuntimeException(sprintf('Product "%s" found but not in disabled state', $arg1));
            }
        }

        throw new RuntimeException(sprintf('Product "%s" not found', $arg1));
    }
}
