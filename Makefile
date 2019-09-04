# Twilio API helper library.
# See LICENSE file for copyright and license details.

COMPOSER = $(shell which composer)
ifeq ($(strip $(COMPOSER)),)
	COMPOSER = php composer.phar
endif
PHPVERSION = $(shell php -r 'echo PHP_VERSION;')

all: test

clean:
	@rm -rf venv vendor composer.lock
	@git checkout composer.json

install: clean
	@composer --version || (curl -s https://getcomposer.org/installer | php);
	$(COMPOSER) config platform.php $(PHPVERSION)
	$(COMPOSER) install

vendor: install

# if these fail, you may need to install the helper library
test: install
	$(COMPOSER) require --dev phpunit/phpunit
	@PATH=vendor/bin:$(PATH) phpunit --strict-coverage --disallow-test-output --colors --configuration tests/phpunit.xml

docs-install:
	curl "https://github.com/ApiGen/ApiGen/releases/download/v4.1.2/apigen.phar" --create-dirs -L -o bin/apigen

docs: docs-install
	bin/apigen generate -s ./ -d docs/api --exclude="Tests/*" --exclude="vendor/*" --exclude="autoload.php" --template-theme bootstrap --main Twilio

authors:
	echo "Authors\n=======\n\nA huge thanks to all of our contributors:\n\n" > AUTHORS.md
	git log --raw | grep "^Author: " | cut -d ' ' -f2- | cut -d '<' -f1 | sed 's/^/- /' | sort | uniq >> AUTHORS.md

# Required to fix the php:5.5 Docker image, as one of the repositories does not exist anymore
docker-php5-sources:
	echo 'deb http://httpredir.debian.org/debian jessie main' > /etc/apt/sources.list
	echo 'deb http://security.debian.org jessie/updates main' >> /etc/apt/sources.list

docker-infra:
	apt-get -q update
	apt-get -qy install git zip unzip

API_DEFINITIONS_SHA=$(shell git log --oneline | grep Regenerated | head -n1 | cut -d ' ' -f 5)
docker-build:
	docker build -t twilio/twilio-php .
	docker tag twilio/twilio-php twilio/twilio-php:${TRAVIS_TAG}
	docker tag twilio/twilio-php twilio/twilio-php:apidefs-${API_DEFINITIONS_SHA}
	docker tag twilio/twilio-php twilio/twilio-php:latest

docker-push:
	echo "${DOCKER_PASSWORD}" | docker login -u "${DOCKER_USERNAME}" --password-stdin
	docker push twilio/twilio-php:${TRAVIS_TAG}
	docker push twilio/twilio-php:apidefs-${API_DEFINITIONS_SHA}
	docker push twilio/twilio-php:latest

.PHONY: all clean test docs docs-install test-install authors
