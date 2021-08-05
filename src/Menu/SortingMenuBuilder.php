<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class SortingMenuBuilder
{
	public function buildMenu(MenuBuilderEvent $event): void
	{
		$sales = $event
			->getMenu()
			->getChild('catalog');

		if ($sales !== null) {
			$sales
				->addChild('sorting', [
					'route' => 'threebrs_admin_sorting_index',
				])
				->setName('threebrs.ui.sortingPlugin.menuTitle')
				->setLabelAttribute('icon', 'sort');
		}
	}
}
