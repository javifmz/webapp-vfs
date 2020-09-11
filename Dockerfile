# Production Dockerfile

# WEB: Build stage
FROM node:lts-alpine as web-build-stage
WORKDIR /app
COPY web/package.json .
RUN npm install --silent
COPY web .
RUN npm run build

# API: Build stage
FROM php:fpm-alpine as api-build-stage
WORKDIR /app
RUN curl -sS https://getcomposer.org/installer | php
COPY api/composer.json /app/composer.json
RUN php composer.phar install --ignore-platform-reqs --no-ansi --no-dev --no-autoloader --no-interaction --no-scripts
COPY api .
RUN php composer.phar dump-autoload --optimize --no-dev --classmap-authoritative && \
    rm -rf composer.* .dockerignore .env.local Dockerfile* docker-*

# Production stage
FROM javifmz/webapp:0.1.0
COPY --chown=www --from=web-build-stage /app/dist /app/web-base
COPY --chown=www --from=api-build-stage /app /www/api
RUN ls /www/api/migrations
ENV API_PATH /www/api
RUN { \
        echo 'echo "Configuring environment variables..."'; \
        echo 'find /www/web -type f | xargs sed -i "s|API_BASE_URL|${API_BASE_URL}|g"'; \
        echo 'find /www/web -type f | xargs sed -i  "s|WEB_BASE_DIR/|${WEB_BASE_DIR}|g"'; \
        echo 'find /www/web -type f | xargs sed -i  "s|WEB_BASE_DIR|${WEB_BASE_DIR}|g"'; \
        echo 'echo "Environment variables configured"'; \
    } > /app/init.sh && \
    { \
        echo 'echo "Migrating database..."'; \
        echo 'php /www/api/migrations/migrate.php'; \
        echo 'echo "Database migrated successfully"'; \
        echo 'sleep infinity'; \
    } > /app/start.sh
