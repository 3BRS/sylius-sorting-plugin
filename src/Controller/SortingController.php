<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Repository\ProductTaxonRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class SortingController
{
    public function __construct(
        private Environment $templatingEngine,
        private TaxonRepositoryInterface $taxonRepository,
        private ProductTaxonRepositoryInterface $productTaxonRepository,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private RouterInterface $router,
        private TranslatorInterface $translator,
    ) {
    }

    public function index(): Response
    {
        return new Response(
            $this->templatingEngine->render(
                '@ThreeBRSSyliusSortingPlugin/index.html.twig',
            ),
        );
    }

    public function products(int $taxonId): Response
    {
        $taxon = $this->taxonRepository->find($taxonId);
        if ($taxon === null) {
            throw new NotFoundHttpException();
        }

        $productsTaxons = $this->productTaxonRepository->findBy(
            ['taxon' => $taxon],
            ['position' => 'asc'],
        );

        return new Response(
            $this->templatingEngine->render(
                '@ThreeBRSSyliusSortingPlugin/index.html.twig',
                [
                    'taxon' => $taxon,
                    'productsTaxons' => $productsTaxons,
                ],
            ),
        );
    }

    public function savePositions(Request $request, Session $session): RedirectResponse
    {
        $i = 0;
        $taxon = null;

        foreach ($request->request->all('id') as $id) {
            $productTaxon = $this->productTaxonRepository->find($id);
            assert($productTaxon instanceof ProductTaxonInterface);
            $productTaxon->setPosition($i);

            if ($taxon === null) {
                $taxon = $productTaxon->getTaxon();
            }

            ++$i;
        }

        $this->entityManager->flush();

        if ($taxon !== null) {
            $message = $this->translator->trans('threebrs.ui.sortingPlugin.successMessage');
            $this->addFlashMessage('success', $message, $session);

            $redirectUrl = $this->router->generate(
                'threebrs_admin_sorting_products',
                ['taxonId' => $taxon->getId()],
            );

            // Eg. for update product position in elasticsearch
            $event = new GenericEvent($taxon);
            $this->eventDispatcher->dispatch(
                $event,
                'threebrs-sorting-products-after-persist',
            );
        } else {
            $message = $this->translator->trans('threebrs.ui.sortingPlugin.noProductMessage');
            $this->addFlashMessage('error', $message, $session);

            $redirectUrl = $this->router->generate('threebrs_admin_sorting_index');
        }

        return new RedirectResponse($redirectUrl);
    }

    private function addFlashMessage(string $type, string $message, Session $session): void
    {
        $session->getFlashBag()->add($type, $message);
    }
}
