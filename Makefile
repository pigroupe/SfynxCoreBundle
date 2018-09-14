include artifacts/Makefile

# Publish new release. Usage:
#   make tag VERSION=(major|minor|patch)
# You need to install https://github.com/flazz/semver/ before
tag: changelog-deb
	@semver inc $(VERSION)
	@echo "New release: `semver tag`"
	@echo Releasing sources
	@sed -i -r "s/(v[0-9]+\.[0-9]+\.[0-9]+)/`semver tag`/g" \
		artifacts/bintray.json \
		artifacts/phar/build.php \
		artifacts/sfynx-generator/Dockerfile \
		Generator/Presentation/Coordination/Command/sfynx-ddd-generator \
		Generator/Application/Application.php \
		Resources/doc/command_generator.md
	@sed -i -r "s/([0-9]{4}\-[0-9]{2}\-[0-9]{2})/`date +%Y-%m-%d`/g" artifacts/bintray.json

# Tag git with last release
release: prepare-build build-phar
	@git add .
	@git commit -m "releasing `semver tag`"
	@(git tag --delete `semver tag`) || true
	@(git push --delete origin `semver tag`) || true
	@git tag `semver tag`
	@git push -u origin 2.x
	@git push origin `semver tag`
	@make build-docker
