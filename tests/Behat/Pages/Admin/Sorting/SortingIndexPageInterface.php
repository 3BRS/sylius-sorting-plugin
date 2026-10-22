<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;

interface SortingIndexPageInterface extends SymfonyPageInterface
{
    public function usesSyliusTaxonTreeController(): bool;

    /**
     * @return list<string>
     */
    public function getTaxonTreeNames(): array;
}
