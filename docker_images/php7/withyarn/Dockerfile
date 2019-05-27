FROM gitx.ru:5005/docker/images/php7:prod
RUN set -ex \
  && apk add --no-cache --update nodejs \
  && touch ~/.profile \
  && curl -o- -L https://yarnpkg.com/install.sh | sh
  
ENV PATH="/root/.yarn/bin:/root/.config/yarn/global/node_modules/.bin:$PATH"
