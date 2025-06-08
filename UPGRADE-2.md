# UPGRADE from Sylius 1.14 to Sylius 2.0

## File Location Changes

* Moved config/ dir. out of src/ and into plugin root dir.
* Moved templates/ dir. out of src/ and into plugin root dir.
* Moved translations/ dir. out of src/ and into plugin root dir.

## Config Changes 

* The main config file is now located at: `config/config.yaml`

* Routing configuration:

    * Main routing file: config/routing.yaml

* Soritng JS and CSS entries are located at: `src/Resources/assets/admin/`

## Twig Hooks

Templates are now rendered using Twig hooks, which is the standard in Sylius 2:

* **Admin**

    * [index.yaml]() contains config for Twig hooks used in Admin
