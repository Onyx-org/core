ONYX_CORE_DIR=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
COMPOSER_ARGS=--ignore-platform-reqs

all: install

install: composer.phar
	php composer.phar install $(COMPOSER_ARGS)

update: composer.phar
	php composer.phar update $(COMPOSER_ARGS)

composer.phar:
	curl -sS https://getcomposer.org/installer | php

dumpautoload: composer.phar
	php composer.phar dumpautoload

clean: remove-deps
	rm -f composer.lock
	rm -f composer.phar

remove-deps:
	rm -rf vendor

phpunit: vendor/bin/phpunit
	docker run -it --rm --name phpunit -v ${ONYX_CORE_DIR}:/usr/src/onyx -w /usr/src/onyx php:7.1-cli vendor/bin/phpunit

vendor/bin/phpunit: install

.PHONY: install update dumpautoload clean remove-deps phpunit
