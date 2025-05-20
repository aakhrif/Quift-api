# Backend API Service (PHP + Slim + GraphQL + JWT Login)

This service provides a lightweight, containerized PHP backend using Slim Framework and GraphQL, with optional local development support for JWT-based login.

## 🚀 Stack

- PHP 8.2 (CLI or FPM)
- Slim Framework
- Firebase PHP-JWT (for auth)
- webonyx/graphql-php
- nginx (as reverse proxy)
- Docker Compose

## 📁 Project Structure

```
backend-api/
├── public/              # Web entry point (index.php)
├── routes/              # REST endpoints (e.g. login.php)
├── includes/            # Shared helpers (e.g. DB.php)
├── graphql/             # GraphQL types and resolvers
├── Dockerfile           # PHP-FPM + Composer
├── docker-compose.yml   # Defines services and network
├── nginx.conf           # Nginx config
└── .env                 # Environment config
```

## 🐳 Usage (Docker)

1. Create Docker network (if not already exists):
   ```bash
   docker network create crm-net
   ```

2. Build and start the containers:
   ```bash
   docker compose up --build -d
   ```

3. Service will be accessible at:  
   `http://localhost:8080/graphql`

---

## ⚙️ Local Dev (PHP CLI)

Start the API locally (Slim only):

```bash
php -S localhost:8000 -t public
```

---

## ✅ Test the GraphQL API

### Via `curl`

```bash
curl -X POST http://localhost:8080/graphql   -H "Content-Type: application/json"   -d '{ "query": "{ hello }" }'
```

---

## 🔐 Test Login Endpoint

### Via `curl`

```bash
curl -X POST http://localhost:8000/login   -H "Content-Type: application/json"   -d '{ "email": "user@example.com", "password": "test1234" }'
```

### Expected response

```json
{
  "token": "eyJhbGciOi..."
}
```

---

## 🛠 Customize

- Define GraphQL types in `/graphql`
- Add REST logic in `/routes`
- Configure DB access in `includes/DB.php`
- Adjust `.env` for local or container DB usage
