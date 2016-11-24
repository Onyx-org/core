all: install

install: composer.phar
	php composer.phar install

update: composer.phar
	php composer.phar update

composer.phar:
	curl -sS https://getcomposer.org/installer | php

dumpautoload: composer.phar
	php composer.phar dumpautoload

clean: remove-deps
	rm -f composer.lock
	rm -f composer.phar

remove-deps:
	rm -rf vendor

.PHONY: install updatedumpautoload clean remove-deps
