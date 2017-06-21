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
	sed -i 's/^COMPOSE_PROJECT_NAME=onyx$$/COMPOSE_PROJECT_NAME=$(COMPOSE_PROJECT_NAME)/g' ./.env
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

wizard-new-repository: .onyx
	$(eval REPOSITORY_NAME := $(shell bash -c 'read -p "Enter your repository name : " repositoryName; echo $$repositoryName'))
	$(eval TARGET_DIR := "src/Persistence")
	# Create directories
	@mkdir -p ${TARGET_DIR}/Repositories
	@mkdir -p src/Domain
	@mkdir -p ${TARGET_DIR}/DataTransferObjects
	# Copy files
	@cp -rf vendor/onyx/core/wizards/repository/* ${TARGET_DIR}
	# Rename files
	@mv ${TARGET_DIR}/__ONYX_RepositoryNameRepository.php ${TARGET_DIR}/${REPOSITORY_NAME}Repository.php
	@mv ${TARGET_DIR}/Repositories/__ONYX_RepositoryName.php ${TARGET_DIR}/Repositories/${REPOSITORY_NAME}.php
	@mv ${TARGET_DIR}/DataTransferObjects/__ONYX_RepositoryName.php ${TARGET_DIR}/DataTransferObjects/${REPOSITORY_NAME}.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_RepositoryName/${REPOSITORY_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Repository created !"
	@echo ""
	@echo "Don't forget to build your repository in your provider"
	@echo ""

wizard-new-query: .onyx
	$(eval QUERY_NAME := $(shell bash -c 'read -p "Enter your query name : " queryName; echo $$queryName'))
	$(eval PARENT_TARGET_DIR := "src/Domain/Queries")
	$(eval TARGET_DIR := "${PARENT_TARGET_DIR}/${QUERY_NAME}")
	# Create directories
	@mkdir -p ${PARENT_TARGET_DIR}
	# Copy files
	@cp -rf vendor/onyx/core/wizards/query/* ${PARENT_TARGET_DIR}
	# Rename files
	@mv ${PARENT_TARGET_DIR}/__ONYX_QueryName ${TARGET_DIR}
	@mv ${TARGET_DIR}/__ONYX_QueryNameQuery.php ${TARGET_DIR}/${QUERY_NAME}Query.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_QueryName/${QUERY_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Query created !"
	@echo ""
	@echo "Don't forget to build your query in your provider"
	@echo ""

wizard-new-command: .onyx
	$(eval COMMAND_NAME := $(shell bash -c 'read -p "Enter your command name : " commandName; echo $$commandName'))
	$(eval PARENT_TARGET_DIR := "src/Domain/Commands")
	$(eval TARGET_DIR := "${PARENT_TARGET_DIR}/${COMMAND_NAME}")
	# Create directories
	@mkdir -p ${PARENT_TARGET_DIR}
	# Copy files
	@cp -rf vendor/onyx/core/wizards/command/* ${PARENT_TARGET_DIR}
	# Rename files
	@mv ${PARENT_TARGET_DIR}/__ONYX_CommandName ${TARGET_DIR}
	@mv ${TARGET_DIR}/__ONYX_CommandNameCommand.php ${TARGET_DIR}/${COMMAND_NAME}Command.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_CommandName/${COMMAND_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Command created !"
	@echo ""
	@echo "Don't forget to build your command in your provider"
	@echo ""
