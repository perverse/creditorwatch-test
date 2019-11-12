FROM php:7.2-cli
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp/public
EXPOSE 8000
CMD [ "php", "-S", "0.0.0.0:8000" ]