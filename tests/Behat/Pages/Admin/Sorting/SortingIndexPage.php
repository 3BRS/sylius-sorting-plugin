<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use Behat\Mink\Element\NodeElement;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;

final class SortingIndexPage extends SymfonyPage implements SortingIndexPageInterface
{
    public function getRouteName(): string
    {
        return 'threebrs_admin_sorting_index';
    }

    /**
     * @return list<string>
     */
    public function getTaxonTreeStimulusControllers(): array
    {
        $page = $this->getSession()->getPage();
        /** @var list<NodeElement> $nodes */
        $nodes = $page->findAll('css', '[data-controller~="taxon-tree"], [data-controller~="sylius--admin-bundle--taxon-tree"]');

        $controllers = [];
        foreach ($nodes as $node) {
            $value = $node->getAttribute('data-controller');
            if ($value === null) {
                continue;
            }
            foreach (preg_split('/\s+/', trim($value)) as $name) {
                if ($name === '') {
                    continue;
                }
                $controllers[] = $name;
            }
        }

        return $controllers;
    }
}
