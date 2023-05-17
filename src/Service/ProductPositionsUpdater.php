<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use ThreeBRS\SortingPlugin\Event\SortingPluginEvents;
use ThreeBRS\SortingPlugin\Service\Exception\ProductPositionsUpdaterException;
use Throwable;

class ProductPositionsUpdater
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher,
        private TranslatorInterface $translator,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @throws ProductPositionsUpdaterException
     */
    public function process(Request $request): ?TaxonInterface
    {
        $sessionBag = $this->getSessionBag($request);

        /**
         * @var array<int|string, int> $identifiersPositionsMap Key: PK, Value: index
         */
        $identifiersPositionsMap = array_flip(array_values($request->get('id', [])));

        if ($identifiersPositionsMap === []) {
            $this->setSessionError('threebrs.ui.sortingPlugin.chooseTaxon', $sessionBag);

            return null;
        }

        $productTaxonCollection = $this->entityManager
            ->getRepository(ProductTaxonInterface::class)
            ->findBy(['id' => array_keys($identifiersPositionsMap)]);

        if (count($productTaxonCollection) !== count($identifiersPositionsMap)) {
            throw new ProductPositionsUpdaterException('Required identifiers set is invalid.');
        }

        $taxon = null;

        foreach ($productTaxonCollection as $productTaxon) {
            assert($productTaxon instanceof ProductTaxonInterface);
            $taxon ??= $productTaxon->getTaxon();
            $productTaxon->setPosition($identifiersPositionsMap[$productTaxon->getId()] ?? 0);
        }

        if ($taxon === null) {
            $this->setSessionError('threebrs.ui.sortingPlugin.noProductMessage', $sessionBag);

            return null;
        }

        foreach ($productTaxonCollection as $productTaxon) {
            $productTaxon->setPosition($identifiersPositionsMap[$productTaxon->getId()] ?? 0);
            $this->entityManager->persist($productTaxon);
        }

        $this->entityManager->flush();

        $this->setSessionSuccess('threebrs.ui.sortingPlugin.successMessage', $sessionBag);
        $this->eventDispatcher->dispatch(
            new GenericEvent($taxon),
            SortingPluginEvents::SORTING_PRODUCTS_AFTER_PERSIST
        );

        return $taxon;
    }

    private function prepareSessionMessage(string $key): string
    {
        try {
            return $this->translator->trans($key);
        } catch (Throwable $throwable) {
            $this->logger->error($throwable, ['throwable' => $throwable, 'key' => $key]);

            return $key;
        }
    }

    private function setSessionError(string $messageKey, FlashBagInterface $sessionBag): void
    {
        $sessionBag->add(
            'error',
            $this->prepareSessionMessage($messageKey)
        );
    }

    private function setSessionSuccess(string $messageKey, FlashBagInterface $sessionBag): void
    {
        $sessionBag->add(
            'success',
            $this->prepareSessionMessage($messageKey),
        );
    }

    private function getSessionBag(Request $request): FlashBagInterface
    {
        $sessionBag = $request->getSession()->getBag('flashes');
        assert($sessionBag instanceof FlashBagInterface);

        return $sessionBag;
    }
}
