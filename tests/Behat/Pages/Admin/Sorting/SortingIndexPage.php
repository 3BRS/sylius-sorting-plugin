<?php

declare(strict_types=1);

namespace Tests\ThreeBRS\SortingPlugin\Behat\Pages\Admin\Sorting;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Webmozart\Assert\Assert;

final class SortingIndexPage extends SymfonyPage implements SortingIndexPageInterface
{
    private const TAXON_TREE_CONTROLLER = 'sylius--admin-bundle--taxon-tree';

    public function getRouteName(): string
    {
        return 'threebrs_admin_sorting_index';
    }

    public function usesSyliusTaxonTreeController(): bool
    {
        if (!$this->hasElement('taxon_tree')) {
            return false;
        }

        $taxonTree = $this->getElement('taxon_tree');

        return $taxonTree->has('css', sprintf('[data-%s-target="tree"]', self::TAXON_TREE_CONTROLLER)) &&
            $taxonTree->has('css', sprintf('[data-%s-target="itemPrototyp"]', self::TAXON_TREE_CONTROLLER));
    }

    public function getTaxonTreeNames(): array
    {
        $treeData = $this->getElement('taxon_tree')->getAttribute(sprintf('data-%s-tree-data-value', self::TAXON_TREE_CONTROLLER));
        Assert::string($treeData, 'The taxon tree has no tree data.');

        $tree = json_decode($treeData, true, flags: \JSON_THROW_ON_ERROR);
        Assert::isArray($tree);

        return $this->collectNames($tree);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'taxon_tree' => sprintf('[data-controller~="%s"]', self::TAXON_TREE_CONTROLLER),
        ]);
    }

    /**
     * @param array<mixed> $nodes
     *
     * @return list<string>
     */
    private function collectNames(array $nodes): array
    {
        $names = [];
        foreach ($nodes as $node) {
            Assert::isArray($node);
            Assert::string($node['name']);
            Assert::isArray($node['children']);

            $names[] = $node['name'];
            array_push($names, ...$this->collectNames($node['children']));
        }

        return $names;
    }
}
