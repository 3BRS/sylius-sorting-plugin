<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ThreeBRS\SortingPlugin\Service\ProductPositionsUpdater;
use ThreeBRS\SortingPlugin\Service\TaxonsAccessor;
use Twig\Environment;

class SortingController
{
    public function __construct(
        private Environment $templatingEngine,
        private TaxonsAccessor $taxonsAccessor,
        private ProductPositionsUpdater $productPositionsUpdater,
    )
    {
    }

    public function index(): Response
    {
        return new Response(
            $this->templatingEngine->render(
                '@ThreeBRSSyliusSortingPlugin/index.html.twig',
            )
        );
    }

    public function products(int $taxonId): Response
    {
        $this->taxonsAccessor->initialize($taxonId);

        return new Response(
            $this->templatingEngine->render(
                '@ThreeBRSSyliusSortingPlugin/index.html.twig',
                [
                    'taxon' => $this->taxonsAccessor->getTaxon(),
                    'productsTaxons' => $this->taxonsAccessor->getProductsTaxons(),
                ]
            )
        );
    }

    public function savePositions(Request $request): RedirectResponse
    {
        return new RedirectResponse($this->productPositionsUpdater->process($request));
    }
}
