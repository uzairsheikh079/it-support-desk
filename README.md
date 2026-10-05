# IT Support Desk

[![CI](https://github.com/uzairsheikh079/it-support-desk/actions/workflows/ci.yml/badge.svg)](https://github.com/uzairsheikh079/it-support-desk/actions/workflows/ci.yml)

A production-minded support-ticket platform for office IT operations. The project demonstrates full-stack Laravel development, a versioned REST API, secure request handling, asynchronous RabbitMQ jobs, automated testing, and a reproducible Docker environment.

## Highlights

- Authenticated operations dashboard with responsive Tailwind CSS interface
- Ticket creation, filtering, assignment, status transitions, and resolution tracking
- UUID-based public identifiers instead of sequential IDs in URLs
- Versioned JSON API protected by a constant-time API-key middleware
- Form Request validation and explicit mass-assignment boundaries
- RabbitMQ-backed queue for asynchronous ticket activity jobs
- MySQL production-style runtime and SQLite test environment
- PHPUnit coverage for authentication, validation, API behavior, escaping, and lifecycle changes
- GitHub Actions pipeline for frontend builds, tests, and dependency audits

## Stack

- PHP 8.5 and Laravel 13
- MySQL 8.4
- RabbitMQ 4
- Tailwind CSS 4 and Vite 8
- PHPUnit 12
- Docker Compose

## Architecture

The web and API controllers share focused ticket actions and validation requests. Eloquent enums keep status and priority values constrained, while API Resources provide a stable JSON contract. Ticket creation dispatches a queued job to the `support` queue; Docker Compose runs a dedicated RabbitMQ worker alongside the application.

## Run with Docker

```bash
git clone https://github.com/uzairsheikh079/it-support-desk.git
cd it-support-desk
docker compose build
docker compose run --rm app php artisan migrate --seed --force
docker compose up
```

Open `http://localhost:8000`.

Demo sign-in:

```text
Email: admin@example.com
Password: password
```

RabbitMQ management is available at `http://localhost:15672` with the local Docker credentials `supportdesk` / `supportdesk`.

> The bundled credentials and keys are local development defaults only. Replace them before any public deployment.

## Local setup

```bash
cp .env.example .env
composer install
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Run the RabbitMQ worker separately when `QUEUE_CONNECTION=rabbitmq`:

```bash
php artisan queue:work rabbitmq --queue=support --tries=3
```

## REST API

All endpoints use the `/api/v1` prefix and require an `X-API-Key` header.

```bash
curl --header "X-API-Key: local-development-api-key" \
  http://localhost:8000/api/v1/tickets
```

Available operations:

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/api/v1/tickets` | List and filter tickets |
| `POST` | `/api/v1/tickets` | Create a ticket |
| `GET` | `/api/v1/tickets/{uuid}` | Retrieve a ticket |
| `PUT` | `/api/v1/tickets/{uuid}` | Update a ticket |
| `DELETE` | `/api/v1/tickets/{uuid}` | Delete a ticket |

List filters: `search`, `status`, and `priority`.

## Quality checks

```bash
vendor/bin/pint --format agent
php artisan test --compact
composer audit
npm audit --audit-level=high
npm run build
```

## Portfolio context

This is an original portfolio project. It contains no employer, customer, patient, or proprietary Informadent code or data.
