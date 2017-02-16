include .onyx

.PHONY: wizard-set-namespace core-wizard-set-namespace wizard-new-controller

.onyx:
	$(error .onyx file is missing)

convert-namespace = $(subst ::,\\,$1)

wizard-set-namespace: core-wizard-set-namespace composer-dumpautoload

core-wizard-set-namespace:
	$(info )
	$(info Backslashes must be replaced by ::. Example : My::Onyx::Skeleton::Namespace)
	$(info )
	# This line below breaks the make autocompletion. Need to find why
	$(eval NEW_NAMESPACE := $(shell bash -c 'read -p "Enter your application newNamespace : " newNamespace; echo $$newNamespace'))
	$(eval BACKSLASHED_NAMESPACE := $(call convert-namespace,$(NAMESPACE)))
	$(eval BACKSLASHED_NEW_NAMESPACE := $(call convert-namespace,$(NEW_NAMESPACE)))
	$(eval ESCAPED_NAMESPACE := $(subst \\,\\\\,$(BACKSLASHED_NAMESPACE)))
	$(eval ESCAPED_NEW_NAMESPACE := $(subst \\,\\\\,$(BACKSLASHED_NEW_NAMESPACE)))
	$(eval COMPOSE_PROJECT_NAME := $(shell echo $(NEW_NAMESPACE)| sed  's#::#-#'))
	find src/ tests/ www/ -type f -exec sed -i 's/${BACKSLASHED_NAMESPACE}/$(BACKSLASHED_NEW_NAMESPACE)/g' {} \;
	sed -i 's/${BACKSLASHED_NAMESPACE}/$(BACKSLASHED_NEW_NAMESPACE)/g' console
	sed -i 's/${ESCAPED_NAMESPACE}\\\\/${ESCAPED_NEW_NAMESPACE}\\\\/g' ./composer.json
	sed -i 's/^NAMESPACE=.*$$/NAMESPACE=$(NEW_NAMESPACE)/' ./.onyx
	sed -i 's/^export COMPOSE_PROJECT_NAME=onyx$$/export COMPOSE_PROJECT_NAME=$(COMPOSE_PROJECT_NAME)/g' ./docker/helpers.mk
	@echo ""
	@echo "Namespace updated !"
	@echo ""

wizard-new-controller: .onyx
	$(eval CONTROLLER_NAME := $(shell bash -c 'read -p "Enter your controller name : " controllerName; echo $$controllerName'))
	$(eval TARGET_DIR := "src/Controllers/${CONTROLLER_NAME}")
	@cp -rf vendor/onyx/core/wizards/controller/* src/Controllers
	@mv src/Controllers/__ONYX_ControllerName ${TARGET_DIR}
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_ControllerName/${CONTROLLER_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	@echo "Controller created !"
	@echo ""
	@echo "Don't forget to mount your new provider in src/Application.php :"
	@echo ""
	@echo "$$ this->mount('/', new Controllers\\\\${CONTROLLER_NAME}\Provider());"
	@echo ""
