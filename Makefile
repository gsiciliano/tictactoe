.PHONY: *


help:                                   ## Show this help.
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'
up:                                     ## Turn on container services
	docker-compose --file docker-compose.dev.yml up -d
stop:                                   ## Turn off container services
	docker-compose --file docker-compose.dev.yml stop
down:                                   ## Turn on and remove container services
	docker-compose --file docker-compose.dev.yml down
build:                                  ## Build container images
	docker-compose --file docker-compose.dev.yml build
rebuild:                                ## Rebuild and turn on container services
	docker-compose --file docker-compose.dev.yml up -d --build
shell:                                  ## Enter application container shell
	docker exec -it tic_tac_toe_app bash

.DEFAULT_GOAL := help
