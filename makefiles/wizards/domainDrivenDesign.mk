.PHONY: wizard-tree-bounded-context wizard-tree-aggregate-root wizard-new-controller wizard-new-query wizard-new-command

ask_for = $(shell bash -c 'read -p "Enter your$1 [ex:$2] : " reply; echo $$reply')

convert-namespace = $(subst ::,\\,$1)
convert-path-into-namespace = $(subst /,\\,$1)
extract-last-dir = $(lastword $(subst /, ,$1))
lc = $(shell echo $1 | tr A-Z a-z)

TREE:=$(shell if [ $$(which tree) ]; then echo "tree -d -L 4"; else echo "ls -lh --group-directories-first --color=auto"; fi)

BC_ROOT_DIRS := assets config data src tests views
BC_SRC_DIRS := src/Domain src/Application src/Infrastructure
BC_SRC_APP_DIRS := src/Application/Commands src/Application/Queries
BC_SRC_INFRA_DIRS := src/Infrastructure/Console src/Infrastructure/Controllers/Back src/Infrastructure/Controllers/Front src/Infrastructure/Persistence src/Infrastructure/Services src/Infrastructure/Workers 
BC_TESTS_DIRS := tests/integration tests/unit/Application/Commands tests/unit/Application/Queries tests/unit/Domain tests/unit/Infrastructure 
BC_DIRS := $(BC_ROOT_DIRS) $(BC_SRC_DIRS) $(BC_SRC_APP_DIRS) $(BC_SRC_INFRA_DIRS) $(BC_TESTS_DIRS)

wizard-tree-bounded-context:
	$(eval BOUNDED_CONTEXT := $(call ask_for, 'Bounded_context', 'Editorial'))
	$(eval TARGET_DIR := "src/${BOUNDED_CONTEXT}")
	@mkdir -p ${TARGET_DIR}
	@for DIR in $(BC_DIRS) ; do mkdir -p ${TARGET_DIR}/$${DIR}; done
	$(TREE) ${TARGET_DIR} 

#------------------------------------------------------------------------------
AGG_DIRS := Collections Entities Exceptions Factories ValueObjects

wizard-tree-aggregate-root:
	$(eval BOUNDED_CONTEXT := $(call ask_for, 'Bounded_context', 'Editorial'))
	$(eval AGGREGATE_ROOT := $(call ask_for, 'Aggregate_root', 'Menu'))
	$(eval TARGET_DIR := "src/${BOUNDED_CONTEXT}/src/Domain/${AGGREGATE_ROOT}")
	@mkdir -p ${TARGET_DIR}
	@for DIR in $(AGG_DIRS); do mkdir -p ${TARGET_DIR}/$${DIR}; done
	$(TREE) ${TARGET_DIR} 

#------------------------------------------------------------------------------

wizard-new-controller: .onyx
	$(eval BOUNDED_CONTEXT := $(call ask_for, 'Bounded_context', 'Editorial'))
	$(eval BACK_OR_FRONT := $(call ask_for, 'Back_or_Front', 'Back'))
	$(eval CONTROLLER_NAME := $(call ask_for, 'Controller', 'Content'))
	$(eval CONTROLLERS := src/${BOUNDED_CONTEXT}/src/Infrastructure/Controllers/${BACK_OR_FRONT})
	$(eval TARGET_DIR := ${CONTROLLERS}/${CONTROLLER_NAME})
	@mkdir -p ${CONTROLLERS}
	@cp -rf vendor/onyx/core/wizards/domainDrivenDesign/controller/* ${CONTROLLERS}
	@mv ${CONTROLLERS}/__ONYX_ControllerName ${TARGET_DIR}
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BoundedContext_LC/$(call lc,${BOUNDED_CONTEXT})/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BackOrFront_LC/$(call lc,${BACK_OR_FRONT})/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_ControllerName_LC/$(call lc,${CONTROLLER_NAME})/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BoundedContext/${BOUNDED_CONTEXT}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BackOrFront/${BACK_OR_FRONT}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_ControllerName/${CONTROLLER_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	@echo "Controller created !"
	@echo ""
	@echo "Don't forget to mount your new provider in src/${BOUNDED_CONTEXT}/src/Controllers.php :"
	@echo ""
	@echo "    '<prefix>', new ${BACK_OR_FRONT}\\\\${CONTROLLER_NAME}\Provider(),"
	@echo ""

#------------------------------------------------------------------------------

wizard-new-query: .onyx
	$(eval BOUNDED_CONTEXT := $(call ask_for, 'Bounded_context', 'Editorial'))
	$(eval QUERY_FULL_PATH := $(call ask_for, 'Query_name', 'Pony/Dimensions'))
	$(eval QUERY_NAME := $(call extract-last-dir,${QUERY_FULL_PATH}))
	$(eval TARGET_DIR := "src/${BOUNDED_CONTEXT}/src/Application/Queries/${QUERY_FULL_PATH}")
	# Create directories
	@mkdir -p ${TARGET_DIR}
	# Copy files
	@cp -rf vendor/onyx/core/wizards/domainDrivenDesign/query/* ${TARGET_DIR}
	# Rename files
	@mv ${TARGET_DIR}/__ONYX_QueryNameQuery.php ${TARGET_DIR}/${QUERY_NAME}Query.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BoundedContext/${BOUNDED_CONTEXT}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's,__ONYX_QueryNamespace,$(call convert-path-into-namespace,${QUERY_FULL_PATH}),g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_QueryName/${QUERY_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Query created !"
	@echo ""
	@echo "Don't forget to build your query handler in your container"
	@echo ""
	
#------------------------------------------------------------------------------

wizard-new-command: .onyx
	$(eval BOUNDED_CONTEXT := $(call ask_for, 'Bounded_context', 'Editorial'))
	$(eval COMMAND_FULL_PATH := $(call ask_for, 'Command_name', 'Pony/Mount'))
	$(eval COMMAND_NAME := $(call extract-last-dir,${COMMAND_FULL_PATH}))
	$(eval TARGET_DIR := "src/${BOUNDED_CONTEXT}/src/Application/Commands/${COMMAND_FULL_PATH}")
	# Create directories
	@mkdir -p ${TARGET_DIR}
	# Copy files
	@cp -rf vendor/onyx/core/wizards/domainDrivenDesign/command/* ${TARGET_DIR}
	# Rename files
	@mv ${TARGET_DIR}/__ONYX_CommandNameCommand.php ${TARGET_DIR}/${COMMAND_NAME}Command.php
	# Replace placeholders in code
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_BoundedContext/${BOUNDED_CONTEXT}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's,__ONYX_CommandNamespace,$(call convert-path-into-namespace,${COMMAND_FULL_PATH}),g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_CommandName/${COMMAND_NAME}/g' {} \;
	@find ${TARGET_DIR} -type f -exec sed -i 's/__ONYX_Namespace/$(call convert-namespace,$(NAMESPACE))/g' {} \;
	# Done
	@echo "Command created !"
	@echo ""
	@echo "Don't forget to build your command handler in your container"
	@echo ""
