# Application Architecture Documentation

This document provides a detailed explanation of how the application works, its components, and their interactions.

## Table of Contents
1. [Application Flow](#application-flow)
2. [Core Components](#core-components)
3. [Authentication & Authorization](#authentication--authorization)
4. [Database Integration](#database-integration)
5. [File Handling](#file-handling)
6. [API Endpoints](#api-endpoints)

## Application Flow

1. **Request Handling**:
   - All requests go through `index.php`
   - Session is started and basic headers are set
   - Required files are included
   - Router instance is created and routes are registered
   - Router matches the URL to a controller action

2. **Routing Process**:
   - The Router (`src/Router.php`) uses a simple routing system
   - Routes are defined with HTTP method, URL pattern, controller class, and action
   - Some routes require authentication (marked with `true` parameter)
   - URL parameters are extracted and passed to controller actions

3. **Controller Processing**:
   - Controllers extend `BaseController` for common functionality
   - Each controller action typically:
     - Validates input
     - Interacts with models/repositories
     - Renders views or returns JSON for API endpoints

## Core Components

### Router (`src/Router.php`)
- Singleton pattern implementation
- Handles URL routing and middleware
- Supports regex patterns in routes
- Example route registration:
  ```php
  $router->addRoute('GET', '/items', ItemController::class, 'index');
  ```

### Base Controller (`src/Controller/BaseController.php`)
- Parent class for all controllers
- Provides common functionality:
  - View rendering
  - Session handling
  - Authentication checks
  - Response formatting

### Controllers
1. **AuthController**:
   - Handles user authentication
   - Login/Register functionality
   - Session management

2. **ItemController**:
   - Manages item CRUD operations
   - Handles file uploads
   - Provides API endpoints

3. **ManagementController**:
   - User profile management
   - Account settings
   - Activity tracking

4. **AccountController**:
   - Legacy account management
   - Profile updates
   - Password changes

### Models
- Extend `BaseModel`
- Represent database tables
- Contain business logic
- Main models:
  - User
  - Item
  - Profile

### Repositories
- Handle database operations
- Extend `BaseRepository`
- Implement data access patterns
- Example repository methods:
  ```php
  public function findById($id)
  public function create(array $data)
  public function update($id, array $data)
  public function delete($id)
  ```

## Authentication & Authorization

1. **Session Management**:
   - PHP sessions used for authentication
   - Session started in `index.php`
   - Session data stored server-side

2. **Login Process**:
   ```php
   // AuthController.php
   public function login() {
       // Validate credentials
       // Create session
       // Redirect to dashboard
   }
   ```

3. **Authentication Middleware**:
   - Routes can require authentication
   - Implemented in Router class
   - Redirects to login if unauthorized

## Database Integration

1. **Configuration**:
   - Database credentials in environment variables
   - PostgreSQL connection handled by Database class
   - Connection pooling via PHP-FPM

2. **Query Execution**:
   - Prepared statements for security
   - Transaction support
   - Error handling and logging

3. **Example Database Operations**:
   ```php
   // ItemRepository.php
   public function findById($id) {
       $query = "SELECT * FROM items WHERE id = $1";
       return $this->db->executeQuery($query, [$id]);
   }
   ```

## File Handling

1. **Upload Process**:
   - File validation
   - MIME type checking
   - Size limits
   - Secure storage

2. **Blob Storage**:
   - Files stored in PostgreSQL BYTEA columns
   - Efficient retrieval system
   - Caching implementation

## API Endpoints

The application provides several RESTful API endpoints:

### Authentication
```
POST /login
POST /logout
POST /register
```

### Item Management
```
GET    /api/items
GET    /api/items/:id
POST   /items/create
POST   /items/:id/edit
POST   /items/:id/delete
```

### User Management
```
GET    /management/profile
POST   /management/profile/update
POST   /management/account/update
POST   /management/account/password
```

### Response Format
All API endpoints return JSON responses in the format:
```json
{
    "status": "success|error",
    "data": {},
    "message": "Optional message"
}
```

## Security Considerations

1. **Input Validation**:
   - All user input is validated
   - XSS prevention
   - SQL injection prevention

2. **Session Security**:
   - Secure session configuration
   - CSRF protection
   - Session fixation prevention

3. **File Upload Security**:
   - MIME type validation
   - Size restrictions
   - Malware scanning capability

## Error Handling

1. **Error Types**:
   - Application errors
   - Database errors
   - Validation errors
   - Authentication errors

2. **Logging**:
   - Error logging to files
   - Separate logs for different error types
   - Log rotation implementation

## Development Guidelines

1. **Coding Standards**:
   - PSR-12 compliance
   - Type hinting
   - DocBlock comments

2. **Best Practices**:
   - DRY (Don't Repeat Yourself)
   - SOLID principles
   - Clean Code principles

3. **Testing**:
   - Unit testing setup
   - Integration testing
   - API testing 