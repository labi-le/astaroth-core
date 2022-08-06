TAG = denissliva/psalm
VERSION = php8.1
DESTINATION = .
DEBUG_COMMAND = /bin/bash

default: build

test:
	./vendor/bin/phpunit  --no-configuration --bootstrap vendor/autoload.php tests

bench:
	./vendor/bin/phpbench run --report=default

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

cs:
	./vendor/bin/php-cs-fixer fix src/