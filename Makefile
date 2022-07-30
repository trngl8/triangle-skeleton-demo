SHELL := /bin/bash

cc:
	php bin/console cache:clear
.PHONY: cc

update:
	composer update
	yarn upgrade
.PHONY: update

deploy:
	echo "TODO: create deploy process"
.PHONY: deploy

tests:
	php bin/console doctrine:database:drop --env=test --force
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:create --env=test
	php bin/console doctrine:fixtures:load --env=test --no-interaction
	php bin/phpunit --configuration phpunit.xml.dist tests
.PHONY: tests

trans:
	#php bin/console translation:extract --force --format=yaml --as-tree=3 --sort=asc --domain=messages en
.PHONY: trans
