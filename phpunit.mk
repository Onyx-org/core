#------------------------------------------------------------------------------
# PHPUnit
#------------------------------------------------------------------------------
phpunit = docker run -it --rm --name phpunit \
	                 -v ${ONYX_CORE_DIR}:/usr/src/onyx \
	                 -w /usr/src/onyx \
	                 -u ${USER_ID}:${GROUP_ID} \
	                 onyx/core/phpunit \
	                 vendor/bin/phpunit $1 $(CLI_ARGS)

phpunit: vendor/bin/phpunit create-phpunit-image
	$(call phpunit, )

phpunit-coverage: vendor/bin/phpunit create-phpunit-image
	$(call phpunit, --coverage-html=coverage/)

vendor/bin/phpunit: composer-install

create-phpunit-image:
	docker build -q -t onyx/core/phpunit docker/images/phpunit/

clean-phpunit-image:
	docker rmi onyx/core/phpunit

.PHONY: phpunit create-phpunit-image clean-phpunit-image
