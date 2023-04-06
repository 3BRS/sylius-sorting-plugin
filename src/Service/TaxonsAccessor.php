<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Service;

use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductTaxonRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxonsAccessor
{
    private ?TaxonInterface $taxon = null;

    /**
     * @var array<ProductTaxonInterface>
     */
    private array $productsTaxons = [];

    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
        private ProductTaxonRepositoryInterface $productTaxonRepository,
    )
    {
    }

    public function initialize(int $taxonIdentifier): void
    {
        $taxon = $this->taxonRepository->find($taxonIdentifier);

        if ($taxon === null) {
            throw new NotFoundHttpException('Taxon not found by id: ' . $taxonIdentifier);
        }

        assert($taxon instanceof TaxonInterface);
        $this->taxon = $taxon;

        // @phpstan-ignore-next-line
        $this->productsTaxons = $this->productTaxonRepository->findBy(['taxon' => $this->taxon], ['position' => 'asc']);
    }

    public function getTaxon(): ?TaxonInterface
    {
        return $this->taxon;
    }

    /**
     * @return array<ProductTaxonInterface>
     */
    public function getProductsTaxons(): array
    {
        return $this->productsTaxons;
    }
}
