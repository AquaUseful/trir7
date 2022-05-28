FROM php:7.4-apache

COPY --chown=www-data:www-data ./app /var/www/html/

ARG uid
RUN usermod -u ${uid} www-data

ENV db_path='/var/db/app'

RUN mkdir --parents "${db_path}"
RUN chown -R www-data:www-data "${db_path}"

VOLUME [ "${db_path}" ]
