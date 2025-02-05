phpstan:
	APP_ENV=test bin/phpstan.sh

ecs:
	APP_ENV=test bin/ecs.sh --clear-cache

fix:
	APP_ENV=test bin/ecs.sh --fix

install:
	composer install --no-interaction --no-scripts

backend:
	APP_ENV=test tests/Application/bin/console doctrine:database:drop --force || true
	APP_ENV=test tests/Application/bin/console sylius:install --no-interaction
	APP_ENV=test tests/Application/bin/console doctrine:schema:update --force --complete --no-interaction
	APP_ENV=test tests/Application/bin/console sylius:fixtures:load default --no-interaction

frontend:
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

lint:
	APP_ENV=test bin/symfony-lint.sh

behat:
	APP_ENV=test bin/behat.sh

init: install backend frontend

tests: phpstan ecs lint behat

static: install phpstan ecs lint

ci: init static behat

php-bash:
	docker compose exec --user 1000:1000 php bash
