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
  * Optionally hidden taxon tree and product images to get even more space

<p align="center">
	<img src="https://raw.githubusercontent.com/3BRS/sylius-sorting-plugin/master/doc/sorting.png"/>
</p>

## Installation

1. Run `composer require 3brs/sylius-sorting-plugin`.

2. Register plugin to your `config/bundles.php`:
   ```php
   return [
       ...
       ThreeBRS\SortingPlugin\ThreeBRSSyliusSortingPlugin::class => ['all' => true],
   ];
   ```

3. Import plugin configuration in `config/packages/_sylius.yaml`:
   ```yaml
   imports:
       - { resource: "@ThreeBRSSyliusSortingPlugin/config/config.yaml" }
   ```

4. Import routing configuration in `config/routes.yaml`:
   ```yaml
   threebrs_sorting:
       resource: "@ThreeBRSSyliusSortingPlugin/config/routing.yaml"
       prefix: '%sylius_admin.path_name%'
   ```
5. Import plugin JS in `webpack.config.js`
    ```javascript
    Encore
       .addEntry('threebrs-sorting-admin', path.resolve(__dirname, 'vendor/3brs/sylius-sorting-plugin/assets/admin/js/sorting-plugin.js'))
    ```
## Usage

* Log into admin panel
* Click on `Sorting products` in the Catalog section in main menu
* Select taxon
* Drag and drop cards
* Click `Save positions` button in the top right corner

## Development

### Usage

- Create symlink from .env.dist to .env or create your own .env file
- Develop your plugin in `/src`
- See `bin/` for useful commands

### Testing

After your changes you must ensure that the tests are still passing.

```bash
make ci
```

License
-------
This library is under the MIT license.

Credits
-------
Developed by [3BRS](https://3brs.com)<br>
Forked from [manGoweb](https://github.com/mangoweb-sylius/SyliusSortingPlugin).
