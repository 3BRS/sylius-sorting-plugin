<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use ThreeBRS\SortingPlugin\Service\Exception\ProductPositionsUpdaterException;

class ProductPositionsUpdater
{
    private ?FlashBagInterface $sessionBag = null;

    /**
     * @var array<int|string, int> Key: PK, Value: index
     */
    private array $identifiersPositionsMap = [];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RouterInterface $router,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly TranslatorInterface $translator,
    )
    {
    }

    /**
     * @throws ProductPositionsUpdaterException
     */
    public function process(Request $request): string
    {
        $this->initialize($request);

        if ($this->identifiersPositionsMap === []) {
            $this->setSessionError('threebrs.ui.sortingPlugin.chooseTaxon');
        }

        $productTaxonCollection = $this->entityManager
            ->getRepository(ProductTaxonInterface::class)
            ->findBy(['id' => array_keys($this->identifiersPositionsMap)]);

        if (count($productTaxonCollection) !== count($this->identifiersPositionsMap)) {
            throw new ProductPositionsUpdaterException('Required identifiers set is invalid.');
        }

        $taxon = null;

        foreach ($productTaxonCollection as $productTaxon) {
            assert($productTaxon instanceof ProductTaxonInterface);
            $taxon ??= $productTaxon->getTaxon();
            $productTaxon->setPosition($this->identifiersPositionsMap[$productTaxon->getId()] ?? 0);
        }

        if ($taxon === null) {
            $this->setSessionError('threebrs.ui.sortingPlugin.noProductMessage');

            return $this->router->generate('threebrs_admin_sorting_index');
        }

        $this->entityManager->flush();
        $this->setSessionSuccess('threebrs.ui.sortingPlugin.successMessage');
        $this->eventDispatcher->dispatch(new GenericEvent($taxon), 'threebrs-sorting-products-after-persist');

        return $this->router->generate('threebrs_admin_sorting_products', ['taxonId' => $taxon->getId()]);
    }

    /**
     * @throws ProductPositionsUpdaterException
     */
    private function prepareSessionMessage(string $key): string
    {
        if ($this->sessionBag === null) {
            throw new ProductPositionsUpdaterException('Session\'s state is corrupted.');
        }

        return $this->translator->trans($key);
    }

    /**
     * @throws ProductPositionsUpdaterException
     */
    private function setSessionError(string $messageKey): void
    {
        $this->sessionBag->add(
            'error',
            $this->prepareSessionMessage($messageKey)
        );
    }

    /**
     * @throws ProductPositionsUpdaterException
     */
    private function setSessionSuccess(string $messageKey): void
    {
        $this->sessionBag->add(
            'success',
            $this->prepareSessionMessage($messageKey),
        );
    }

    private function initialize(Request $request): void
    {
        $sessionBag = $request->getSession()->getBag('flashes');
        assert($sessionBag instanceof FlashBagInterface);
        $this->sessionBag = $sessionBag;

        $this->identifiersPositionsMap = array_flip(array_values($request->get('id', [])));
    }
}
