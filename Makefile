ONYX_CORE_DIR=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

ifneq (,$(filter $(firstword $(MAKECMDGOALS)),composer phpunit))
    CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(CLI_ARGS):;@:)
endif

COMPOSER_ARGS=
ifeq (composer, $(firstword $(MAKECMDGOALS)))
    ifneq (,$(filter install update,$(CLI_ARGS)))
        COMPOSER_ARGS=--ignore-platform-reqs
    endif
endif

composer: composer.phar
	php composer.phar $(CLI_ARGS) $(COMPOSER_ARGS)

composer.phar:
	curl -sS https://getcomposer.org/installer | php

clean: remove-deps
	rm -f composer.lock
	rm -f composer.phar

remove-deps:
	rm -rf vendor

phpunit: vendor/bin/phpunit
	docker run -it --rm --name phpunit -v ${ONYX_CORE_DIR}:/usr/src/onyx -w /usr/src/onyx php:7.1-cli vendor/bin/phpunit $(CLI_ARGS)

vendor/bin/phpunit: install

.PHONY: composer clean remove-deps phpunit
