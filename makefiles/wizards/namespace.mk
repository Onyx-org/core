include .onyx

.PHONY: wizard-set-namespace core-wizard-set-namespace

.onyx:
	$(error .onyx file is missing)

ask_for = $(shell bash -c 'read -p "Enter your$1 [ex:$2] : " reply; echo $$reply')

convert-namespace = $(subst ::,\\,$1)

wizard-set-namespace: core-wizard-set-namespace composer-dumpautoload

core-wizard-set-namespace:
	$(info )
	$(info Backslashes must be replaced by ::. Example : My::Onyx::Skeleton::Namespace)
	$(info )
	# This line below breaks the make autocompletion. Need to find why
	$(eval NEW_NAMESPACE := $(call ask_for, 'application newNamespace', 'newNamespace'))
	$(eval BACKSLASHED_NAMESPACE := $(call convert-namespace,$(NAMESPACE)))
	$(eval BACKSLASHED_NEW_NAMESPACE := $(call convert-namespace,$(NEW_NAMESPACE)))
	$(eval ESCAPED_NAMESPACE := $(subst \\,\\\\,$(BACKSLASHED_NAMESPACE)))
	$(eval ESCAPED_NEW_NAMESPACE := $(subst \\,\\\\,$(BACKSLASHED_NEW_NAMESPACE)))
	$(eval COMPOSE_PROJECT_NAME := $(shell echo $(NEW_NAMESPACE)| sed  's#::#-#'))
	find src/ tests/ www/ -type f -exec sed -i 's/${BACKSLASHED_NAMESPACE}/$(BACKSLASHED_NEW_NAMESPACE)/g' {} \;
	sed -i 's/${BACKSLASHED_NAMESPACE}/$(BACKSLASHED_NEW_NAMESPACE)/g' console
	sed -i 's/${ESCAPED_NAMESPACE}\\\\/${ESCAPED_NEW_NAMESPACE}\\\\/g' ./composer.json
	sed -i 's/^NAMESPACE=.*$$/NAMESPACE=$(NEW_NAMESPACE)/' ./.onyx
	sed -i 's/^COMPOSE_PROJECT_NAME=onyx$$/COMPOSE_PROJECT_NAME=$(COMPOSE_PROJECT_NAME)/g' ./.env
	@echo ""
	@echo "Namespace updated !"
	@echo ""
