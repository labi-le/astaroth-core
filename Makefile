TAG = denissliva/psalm
VERSION = php8.1
DESTINATION = .
DEBUG_COMMAND = /bin/bash

default: build

build:
	docker build \
	       --force-rm \
	       --tag "$(TAG):$(VERSION)" \
	       --file Dockerfile \
	       $$PWD

push:
	docker push $(TAG):$(VERSION)

debug:
	docker run \
	       --rm \
	       "$(TAG):$(VERSION)" \
	       --entrypoint $(DEBUG_COMMAND)

run:
	@docker run \
	       --rm \
           --volume $$PWD:/psalm \
           --name "psalm" \
           "$(TAG):$(VERSION)" \
           $(DESTINATION)


release: build push