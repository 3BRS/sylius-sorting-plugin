<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use ThreeBRS\SortingPlugin\Service\ProductPositionsUpdater;
use ThreeBRS\SortingPlugin\Service\TaxonsAccessor;
use Twig\Environment;

class SortingController
{
    public function __construct(
        private Environment $templatingEngine,
        private TaxonsAccessor $taxonsAccessor,
        private ProductPositionsUpdater $productPositionsUpdater,
        private RouterInterface $router,
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
        $processedTaxon = $this->productPositionsUpdater->process($request);
        $redirectUrl = $processedTaxon !== null
            ? $this->router->generate('threebrs_admin_sorting_products', ['taxonId' => $processedTaxon->getId()])
            : $this->router->generate('threebrs_admin_sorting_index');

        return new RedirectResponse($redirectUrl);
    }
}
