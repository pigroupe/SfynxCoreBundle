CURRENT_TAG=$(shell git tag |tail -n 2 |head -n 1)
PREVIOUS_TAG=$(shell git tag |tail -n 1)
BUILD_DIR=releases

# Test
test:
	@mkdir -p ${BUILD_DIR}
	@echo Copying sources
	@mkdir -p /tmp/sfynx-ddd-generator-build/build
	@cp Generator -R /tmp/sfynx-ddd-generator-build
	@cp artifacts -R /tmp/sfynx-ddd-generator-build
	@cp Layers -R /tmp/sfynx-ddd-generator-build
	@cp Test/binary -R /tmp/sfynx-ddd-generator-build
	@rm -Rf /tmp/sfynx-ddd-generator-build/vendor /tmp/sfynx-ddd-generator-build/composer.lock

	@echo Installing dependencies
	@cp /tmp/sfynx-ddd-generator-build/artifacts/composer.json /tmp/sfynx-ddd-generator-build/composer.json
	@cd /tmp/sfynx-ddd-generator-build && composer install --optimize-autoloader --prefer-dist

	@echo Testing phar
	@./vendor/bin/phpunit -c phpunit.xml.dist --group=binary
	@echo "Done"

	@rm -Rf /tmp/sfynx-ddd-generator-build

# Build phar
build-phar:
	@mkdir -p ${BUILD_DIR}
	@echo Copying sources
	@mkdir -p /tmp/sfynx-ddd-generator-build/build
	@cp Generator -R /tmp/sfynx-ddd-generator-build
	@cp artifacts -R /tmp/sfynx-ddd-generator-build
	@cp Layers -R /tmp/sfynx-ddd-generator-build
	@rm -Rf /tmp/sfynx-ddd-generator-build/vendor /tmp/sfynx-ddd-generator-build/composer.lock

	@echo Releasing phar
	@sed -i "s/<VERSION>/$CURRENT_TAG/g" /tmp/sfynx-ddd-generator-build/artifacts/phar/build.php

	@echo Installing dependencies
	@cp /tmp/sfynx-ddd-generator-build/artifacts/composer.json /tmp/sfynx-ddd-generator-build/composer.json
	@cd /tmp/sfynx-ddd-generator-build && composer install --no-dev --optimize-autoloader --prefer-dist

	@echo Building phar
	@cd /tmp/sfynx-ddd-generator-build && php artifacts/phar/build.php
	@cp /tmp/sfynx-ddd-generator-build/build/sfynx-ddd-generator.phar ${BUILD_DIR}/sfynx-ddd-generator.phar
	@chmod +x ${BUILD_DIR}/sfynx-ddd-generator.phar
	@cp ${BUILD_DIR}/sfynx-ddd-generator.phar /usr/local/bin/sfynx-ddd-generator

	@rm -Rf /tmp/sfynx-ddd-generator-build

changelog-deb:
	@echo "sfynx-ddd-generator ($(RELEASE_VERSION)) unstable; urgency=low" >/tmp/changelog
	@echo >> artifacts/changelog
	@git log $(CURRENT_TAG)...HEAD --pretty=format:'   * %s ' --reverse >> /tmp/changelog
	@echo >> /tmp/changelog
	@echo >> /tmp/changelog
	@echo  "  -- Etienne de Longeaux <sfynx@pi-groupe.net>  $(shell date --rfc-2822)" >> /tmp/changelog
	@echo >> /tmp/changelog
	@# prepend to changelog
	@cat /tmp/changelog|cat - artifacts/changelog > /tmp/out && mv /tmp/out artifacts/changelog
	@echo >> artifacts/changelog

prepare-build:
	@rm -f ${BUILD_DIR}/sfynx-ddd-generator.phar

build: prepare-build build-phar

