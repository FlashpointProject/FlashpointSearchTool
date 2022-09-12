FROM node:12
COPY . /app
WORKDIR /app
RUN npm install \
&& wget http://infinity.unstable.life/Flashpoint/Data/flashpoint.sqlite \
&& wget http://infinity.unstable.life/Flashpoint/preferences.json

FROM php:7.4-apache
COPY --from=0 /app /var/www/html
