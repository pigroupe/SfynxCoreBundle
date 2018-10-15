CURRENT_TAG=$(shell git tag |tail -n 2 |head -n 1)
RELEASE_VERSION=$(shell echo ${CURRENT_TAG}|sed -r s/v//)
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

prepare-build:
	@rm -f ${BUILD_DIR}/sfynx-ddd-generator.phar

# Build phar
build-phar:
	@mkdir -p ${BUILD_DIR}
	@echo Copying sources
	@mkdir -p /tmp/sfynx-ddd-generator-build/build
	@cp Generator -R /tmp/sfynx-ddd-generator-build
	@cp artifacts -R /tmp/sfynx-ddd-generator-build
	@cp Layers -R /tmp/sfynx-ddd-generator-build
	@rm -Rf /tmp/sfynx-ddd-generator-build/vendor /tmp/sfynx-ddd-generator-build/composer.lock

	@echo Installing dependencies
	@cp /tmp/sfynx-ddd-generator-build/artifacts/composer.json /tmp/sfynx-ddd-generator-build/composer.json
	@cd /tmp/sfynx-ddd-generator-build && composer install --no-dev --optimize-autoloader --prefer-dist

	@echo Building phar
	@cd /tmp/sfynx-ddd-generator-build && php artifacts/phar/build.php
	@cp /tmp/sfynx-ddd-generator-build/build/sfynx-ddd-generator.phar ${BUILD_DIR}/sfynx-ddd-generator.phar
	@chmod +rx ${BUILD_DIR}/sfynx-ddd-generator.phar
	@cp ${BUILD_DIR}/sfynx-ddd-generator.phar /usr/local/bin/sfynx-ddd-generator

	@rm -Rf /tmp/sfynx-ddd-generator-build

build-docker:
	@(docker build --build-arg UID=1000 --build-arg GID=1000 --build-arg ROOT_PATH='.' -t sfynxdevops/generator:latest artifacts/sfynx-generator) || true
	@(docker push sfynxdevops/generator:latest)
	@(docker tag sfynxdevops/generator:latest sfynxdevops/generator:`semver tag`)
	@(docker push sfynxdevops/generator:`semver tag`)

changelog-deb:
	@echo "sfynx-ddd-generator ($(RELEASE_VERSION)) unstable; urgency=low" >/tmp/changelog
	@echo >> CHANGELOG.md
	@git log $(CURRENT_TAG)...HEAD --pretty=format:'   * %s ' --reverse >> /tmp/changelog
	@echo >> /tmp/changelog
	@echo >> /tmp/changelog
	@echo  "  -- Etienne de Longeaux <sfynx@pi-groupe.net>  $(shell date --rfc-2822)" >> /tmp/changelog
	@echo >> /tmp/changelog
	@# prepend to changelog
	@cat /tmp/changelog|cat - CHANGELOG.md > /tmp/out && mv /tmp/out CHANGELOG.md
	@echo >> CHANGELOG.md
