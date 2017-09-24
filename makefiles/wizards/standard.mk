.PHONY: wizard-new-controller wizard-new-repository wizard-new-query wizard-new-command

ask_for = $(shell bash -c 'read -p "Enter your$1 [ex:$2] : " reply; echo $$reply')

convert-namespace = $(subst ::,\\,$1)
convert-path-into-namespace = $(subst /,\\,$1)
extract-last-dir = $(lastword $(subst /, ,$1))
lc = $(shell echo $1 | tr A-Z a-z)

wizard-new-controller: .onyx
	$(eval BACK_OR_FRONT := $(call ask_for, 'Back_or_Front', 'Back'))
	$(eval CONTROLLER_NAME := $(call ask_for, 'Controller', 'Content'))
	$(eval CONTROLLERS := src/Controllers/${BACK_OR_FRONT})
	$(eval TARGET_DIR := ${CONTROLLERS}/${CONTROLLER_NAME})
	@mkdir -p ${CONTROLLERS}
	@cp -rf vendor/onyx/core/wizards/standard/controller/* ${CONTROLLERS}
	@mv ${CONTROLLERS}/__ONYX_ControllerName ${TARGET_DIR}
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BackOrFront_LC/$(call lc,${BACK_OR_FRONT})/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_ControllerName_LC/$(call lc,${CONTROLLER_NAME})/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BackOrFront/${BACK_OR_FRONT}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_ControllerName/${CONTROLLER_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	@echo "Controller created !"
	@echo ""
	@echo "Don't forget to mount your new provider in src/Application.php :"
	@echo ""
	@echo "$$ this->mount('/', new Controllers\\\\${BACK_OR_FRONT}\\\${CONTROLLER_NAME}\Provider());"
	@echo ""

wizard-new-repository: .onyx
	$(eval REPOSITORY_NAME := $(call ask_for, 'Repository_Name', 'Pony'))
	$(eval TARGET_DIR := "src/Persistence")
	# Create directories
	@mkdir -p ${TARGET_DIR}/Repositories
	@mkdir -p src/Domain
	@mkdir -p ${TARGET_DIR}/DataTransferObjects
	# Copy files
	@cp -rf vendor/onyx/core/wizards/standard/repository/* ${TARGET_DIR}
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
	@echo "Don't forget to build your repository in your container"
	@echo ""

wizard-new-query: .onyx
	$(eval QUERY_FULL_PATH := $(call ask_for, 'Query_Name', 'Pony/Dimensions'))
	$(eval QUERY_NAME := $(call extract-last-dir,${QUERY_FULL_PATH}))
	$(eval TARGET_DIR := "src/Domain/Queries/${QUERY_FULL_PATH}")
	# Create directories
	@mkdir -p ${TARGET_DIR}
	# Copy files
	@cp -rf vendor/onyx/core/wizards/standard/query/* ${TARGET_DIR}
	# Rename files
	@mv ${TARGET_DIR}/__ONYX_QueryNameQuery.php ${TARGET_DIR}/${QUERY_NAME}Query.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's,__ONYX_QueryNamespace,$(call convert-path-into-namespace,${QUERY_FULL_PATH}),g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_QueryName/${QUERY_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Query created !"
	@echo ""
	@echo "Don't forget to build your query handler in your container"
	@echo ""

wizard-new-command: .onyx
	$(eval COMMAND_FULL_PATH := $(call ask_for, 'Command_Name', 'Pony/Mount'))
	$(eval COMMAND_NAME := $(call extract-last-dir,${COMMAND_FULL_PATH}))
	$(eval TARGET_DIR := "src/Domain/Commands/${COMMAND_FULL_PATH}")
	# Create directories
	@mkdir -p ${TARGET_DIR}
	# Copy files
	@cp -rf vendor/onyx/core/wizards/standard/command/* ${TARGET_DIR}
	# Rename files
	@mv ${TARGET_DIR}/__ONYX_CommandNameCommand.php ${TARGET_DIR}/${COMMAND_NAME}Command.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's,__ONYX_CommandNamespace,$(call convert-path-into-namespace,${COMMAND_FULL_PATH}),g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_CommandName/${COMMAND_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Command created !"
	@echo ""
	@echo "Don't forget to build your command handler in your container"
	@echo ""
