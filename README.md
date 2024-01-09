<p align="center">
    <a href="https://www.3brs.com" target="_blank">
        <img src="https://3brs1.fra1.cdn.digitaloceanspaces.com/3brs/logo/3BRS-logo-sylius-200.png"/>
    </a>
</p>

<h1 align="center">
Sorting Plugin
<br />
    <a href="https://packagist.org/packages/3brs/sylius-sorting-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/3brs/sylius-sorting-plugin" />
    </a>
    <a href="https://packagist.org/packages/3brs/sylius-sorting-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/3brs/sylius-sorting-plugin" />
    </a>
    <a href="https://circleci.com/gh/3BRS/sylius-sorting-plugin" title="Build status" target="_blank">
        <img src="https://circleci.com/gh/3BRS/sylius-sorting-plugin.svg?style=shield" />
    </a>
</h1>

## Features

* Sort products in taxons by simple drag and drop
  * Well-arranged overview of all products in the taxon
  * Disabled products greyed out
  * Direct links into product details
  * Optionally hidden taxon tree to get even more space

<p align="center">
	<img src="https://raw.githubusercontent.com/3BRS/sylius-sorting-plugin/master/doc/sorting.png"/>
</p>

## Installation

1. Run `$ composer require 3brs/sylius-sorting-plugin`
1. Add plugin class to your `config/bundles.php`

   ```php
   return [
      ...
      ThreeBRS\SortingPlugin\ThreeBRSSyliusSortingPlugin::class => ['all' => true],
   ];
   ```

1. Import `@ThreeBRSSyliusSortingPlugin/Resources/config/routing.yml` in the `routing.yml`
   ```yaml
   threebrs_sorting:
       resource: "@ThreeBRSSyliusSortingPlugin/Resources/config/routing.yml"
       prefix: /admin
   ```

## Usage

* Log into admin panel
* Click on `Sorting products` in the Catalog section in main menu
* Select taxon
* Drag and drop cards
* Click `Save positions` button in the top right corner

## Development

### Usage

- Alter plugin in `/src`
- See `bin/` dir for useful commands

### Testing

After your changes you must ensure that the tests are still passing.

```bash
composer install
bin/console doctrine:database:create --if-not-exists --env=test
bin/console doctrine:schema:update --complete --force --env=test
yarn --cwd tests/Application install
yarn --cwd tests/Application build

bin/behat
bin/phpstan.sh
bin/ecs.sh
vendor/bin/phpspec run
```

### Opening Sylius with your plugin

1. Install symfony CLI command: https://symfony.com/download
    - hint: for Docker (with Ubuntu) use _Debian/Ubuntu — APT based
      Linux_ installation steps as `root` user and without `sudo` command
        - you may need to install `curl` first ```apt-get update && apt-get install curl --yes```
2. Run app

```bash
(cd tests/Application && APP_ENV=test bin/console sylius:fixtures:load)
(cd tests/Application && APP_ENV=test symfony server:start --dir=public --port=8080)
```

- change `APP_ENV` to `dev` if you need it

License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)<br>
Forked from [manGoweb](https://github.com/mangoweb-sylius/SyliusSortingPlugin).
