# Docker Deployment Guide

## Quick Start

### 1. Create a deployment directory

```bash
mkdir meetkat && cd meetkat
```

### 2. Create .env file

```bash
# Generate APP_KEY
APP_KEY=base64:$(openssl rand -base64 32)
```

Or generate it manually:
```bash
docker run --rm php:8.3-cli php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

### 3. Create docker-compose.yml

```yaml
services:
  app:
    image: ghcr.io/muffn/laravel-demo:latest
    container_name: meetkat
    restart: unless-stopped
    ports:
      - "8080:80"
    environment:
      - APP_KEY=${APP_KEY}
    volumes:
      - app-database:/var/www/html/database

volumes:
  app-database:
```

### 4. Run

```bash
docker compose up -d
```

### 5. Access

Open `http://localhost:8080` in your browser.

## Updating

```bash
docker compose pull
docker compose up -d
```

## Backup

```bash
docker compose exec app cat /var/www/html/database/database.sqlite > backup.sqlite
```

## Logs

```bash
docker compose logs -f
```
