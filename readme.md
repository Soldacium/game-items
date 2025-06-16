# 🎮 PHP Web Application Template

A modern PHP web application template with Docker containerization, PostgreSQL database, and Nginx web server.

## Table of Contents

1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Project Structure](#project-structure)
4. [Setup & Installation](#setup--installation)
5. [Docker Configuration](#docker-configuration)
6. [Application Architecture](#application-architecture)

## Project Overview

This is a modern PHP web application template that demonstrates best practices in PHP development, including:
- MVC architecture
- Docker containerization
- PostgreSQL database integration
- Nginx web server configuration
- Session management
- User authentication and authorization
- File upload handling
- RESTful API endpoints

## Tech Stack

| Component    | Technology      | Version |
|-------------|----------------|---------|
| Backend     | PHP-FPM        | 8.x     |
| Database    | PostgreSQL     | Latest  |
| Web Server  | Nginx         | Latest  |
| Containers  | Docker        | Latest  |

## Project Structure

```
.
├── config/                 # Configuration files
├── docker/                 # Docker configuration
│   ├── db/                # PostgreSQL configuration
│   ├── nginx/             # Nginx configuration
│   └── php/               # PHP configuration
├── public/                # Public assets
├── scripts/               # Utility scripts
├── src/                   # Application source code
│   ├── Config/           # Application configuration
│   ├── Controller/       # Controllers
│   ├── Model/            # Data models
│   ├── Repository/       # Data access layer
│   ├── Router.php        # URL routing
│   └── Utils/            # Utility classes
├── test.php              # Test file
├── views/                # View templates
├── docker-compose.yaml   # Docker services configuration
└── index.php            # Application entry point
```

## Setup & Installation

1. Clone the repository
2. Make sure Docker and Docker Compose are installed on your system
3. Run the following commands:

```bash
# Start the Docker containers
docker compose up -d

# The application will be available at:
# http://localhost:8080
```

## Docker Configuration

The application uses four Docker containers:

1. **web** (Nginx):
   - Serves as the web server
   - Port: 8080:80
   - Configured in `docker/nginx/`

2. **php** (PHP-FPM):
   - Runs the PHP application
   - Custom PHP configuration
   - Configured in `docker/php/`

3. **db** (PostgreSQL):
   - Database server
   - Port: 5433:5432
   - Configured in `docker/db/`

4. **pgadmin** (PostgreSQL Admin):
   - Database management interface
   - Port: 5050:80
   - Access credentials:
     - Email: admin@example.com
     - Password: admin

## Application Architecture

The application follows the MVC (Model-View-Controller) pattern:

- **Controllers** (`src/Controller/`):
  - Handle HTTP requests
  - Process user input
  - Coordinate between Models and Views

- **Models** (`src/Model/`):
  - Represent data structures
  - Contain business logic
  - Interact with the database

- **Views** (`views/`):
  - Handle presentation logic
  - Render HTML templates

- **Router** (`src/Router.php`):
  - Manages URL routing
  - Maps URLs to controller actions
  - Handles authentication middleware

For more detailed information about how the application works, please refer to [ARCHITECTURE.md](docs/ARCHITECTURE.md).

