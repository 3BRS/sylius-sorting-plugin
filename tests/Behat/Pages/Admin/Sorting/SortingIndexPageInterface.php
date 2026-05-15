<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface SortingIndexPageInterface extends SymfonyPageInterface
{
    /**
     * Returns the Stimulus controller identifier(s) attached to the taxon-tree
     * wrapper element. Sylius admin's lazy-loaded TaxonTreeController is
     * registered under "sylius--admin-bundle--taxon-tree"; the bare "taxon-tree"
     * identifier does not resolve through Stimulus Bridge.
     *
     * @return list<string>
     */
    public function getTaxonTreeStimulusControllers(): array;
}
