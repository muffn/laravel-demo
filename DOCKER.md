# Docker Deployment Guide

This guide explains how to deploy Meetkat using Docker.

## Prerequisites

- Docker and Docker Compose installed on your server
- Access to GitHub Container Registry (ghcr.io)

## Quick Start

### 1. Create a deployment directory

```bash
mkdir meetkat && cd meetkat
```

### 2. Create environment file

Create a `.env` file with the following variables:

```bash
# Generate a new app key with: docker run --rm -it php:8.3-cli php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Your application URL (used for generating links)
APP_URL=https://your-domain.com
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
      - APP_NAME=Meetkat
      - APP_ENV=production
      - APP_KEY=${APP_KEY}
      - APP_DEBUG=false
      - APP_URL=${APP_URL:-http://localhost:8080}
      - LOG_CHANNEL=stderr
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
      - SESSION_DRIVER=file
      - CACHE_STORE=file
      - QUEUE_CONNECTION=sync
    volumes:
      - app-storage:/var/www/html/storage
      - app-database:/var/www/html/database
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost/"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

volumes:
  app-storage:
  app-database:
```

### 4. Pull and run

```bash
# Login to GitHub Container Registry (if image is private)
echo $GITHUB_TOKEN | docker login ghcr.io -u muffn --password-stdin

# Pull the latest image
docker compose pull

# Start the application
docker compose up -d
```

### 5. Access the application

Open `http://your-server-ip:8080` in your browser.

## Configuration

### Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_KEY` | Laravel encryption key (required) | - |
| `APP_URL` | Application URL for links | `http://localhost:8080` |
| `APP_NAME` | Application name | `Meetkat` |
| `APP_ENV` | Environment (production/local) | `production` |
| `APP_DEBUG` | Enable debug mode | `false` |
| `LOG_CHANNEL` | Log output channel | `stderr` |
| `DB_CONNECTION` | Database driver | `sqlite` |

### Generating APP_KEY

Generate a secure application key:

```bash
docker run --rm -it php:8.3-cli php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

### Volumes

| Volume | Purpose |
|--------|---------|
| `app-storage` | Laravel storage (logs, cache, sessions) |
| `app-database` | SQLite database file |

## Using with Reverse Proxy (Traefik/nginx)

### With Traefik

```yaml
services:
  app:
    image: ghcr.io/muffn/laravel-demo:latest
    container_name: meetkat
    restart: unless-stopped
    environment:
      - APP_NAME=Meetkat
      - APP_ENV=production
      - APP_KEY=${APP_KEY}
      - APP_DEBUG=false
      - APP_URL=https://meetkat.your-domain.com
      - LOG_CHANNEL=stderr
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
    volumes:
      - app-storage:/var/www/html/storage
      - app-database:/var/www/html/database
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.meetkat.rule=Host(`meetkat.your-domain.com`)"
      - "traefik.http.routers.meetkat.entrypoints=websecure"
      - "traefik.http.routers.meetkat.tls.certresolver=letsencrypt"
      - "traefik.http.services.meetkat.loadbalancer.server.port=80"
    networks:
      - traefik

volumes:
  app-storage:
  app-database:

networks:
  traefik:
    external: true
```

### With nginx reverse proxy

```nginx
server {
    listen 80;
    server_name meetkat.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name meetkat.your-domain.com;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## Updating

```bash
# Pull the latest image
docker compose pull

# Restart with the new image
docker compose up -d

# Optional: Remove old images
docker image prune -f
```

## Backup

### Database backup

```bash
docker compose exec app cat /var/www/html/database/database.sqlite > backup.sqlite
```

### Restore database

```bash
docker compose cp backup.sqlite app:/var/www/html/database/database.sqlite
docker compose exec app chown www-data:www-data /var/www/html/database/database.sqlite
```

## Troubleshooting

### View logs

```bash
docker compose logs -f app
```

### Access container shell

```bash
docker compose exec app sh
```

### Check health status

```bash
docker compose ps
```

## Building Locally

If you want to build the image locally:

```bash
docker build -t meetkat:local .
```

Then update your docker-compose.yml to use `image: meetkat:local`.
