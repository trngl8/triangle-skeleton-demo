SHELL := /bin/bash

cc:
	php bin/console cache:clear
.PHONY: cc

tests:
	php bin/phpunit --configuration phpunit.xml.dist tests
.PHONY: tests

trans:
	#php bin/console translation:extract --force --format=yaml --as-tree=3 --sort=asc --domain=messages en
.PHONY: tests