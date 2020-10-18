IMAGE_NAME="ori-monorepo-builder"
DOCKER=/usr/bin/docker

PROJECT_DIR:=$(dir $(realpath $(lastword $(MAKEFILE_LIST))))

monorepo.build:
	${DOCKER} build -f ./monorepo/Dockerfile -t "${IMAGE_NAME}" ./monorepo/

monorepo.execute:
	${DOCKER} run --rm \
    		-v ${PROJECT_DIR}monorepo/vendor/:/data/monorepo/vendor \
    		-v ${PROJECT_DIR}monorepo/composer.json:/data/monorepo/composer.json \
    		-v ${PROJECT_DIR}monorepo/monorepo-builder.yml:/data/monorepo-builder.yml \
    		-v ${PROJECT_DIR}packages/:/data/packages/ \
    		-v ${PROJECT_DIR}composer.json/:/data/composer.json \
    		-v ${PROJECT_DIR}.git:/data/.git \
    		-v ${HOME}/.ssh/id_rsa:/root/.ssh/id_rsa \
    		-v ${HOME}/.ssh/known_hosts:/root/.ssh/known_hosts \
    		${IMAGE_NAME} \
    		sh -c '$(c)'

monorepo.install:
	make monorepo.execute c='cd /data/monorepo/ && cat composer.json && composer install --no-interaction'

monorepo.init:
	make monorepo.execute c='cd /data/ && monorepo/vendor/bin/monorepo-builder init'

monorepo.validate:
	make monorepo.execute c='cd /data/ && monorepo/vendor/bin/monorepo-builder validate'

monorepo.merge:
	make monorepo.execute c='cd /data/ && monorepo/vendor/bin/monorepo-builder merge'

monorepo.split:
	make monorepo.execute c='cd /data/ && monorepo/vendor/bin/monorepo-builder split'
