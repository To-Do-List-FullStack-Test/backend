# Task Management Application (Todo List)

This full stack application allows you to manage personal tasks with an authentication system, notifications, and real-time task management.

## Project Architecture

The project is divided into two main parts:

### Backend (Laravel)

- **Framework**: Laravel 12
- **Database**: MySQL
- **Authentication**: JWT (JSON Web Token)
- **Real-time**: Pusher for notifications

### Frontend (Vue.js)

- **Framework**: Vue.js 3 with Composition API
- **State Management**: Pinia
- **Routing**: Vue Router
- **HTTP Client**: Axios
- **Real-time**: Laravel Echo with Pusher

## Features

- **Authentication**: Registration, login, and logout
- **Profile Management**: Display and modification of user information
- **Task Management**:
  - Create, read, update, and delete tasks
  - Filter by status and priority
  - Search tasks
  - Mark tasks as completed
- **Notifications**: Real-time notifications for important actions

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js (version 20.19.0 or 22.12.0 or higher)
- npm or yarn

## Installation and Configuration

### 1. Clone the repository

```bash
git clone <repository-url>
cd toDoList
```

### 2. Backend Configuration

```bash
cd todo-backend

# Install dependencies
composer install

# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Generate JWT key
php artisan jwt:secret



# Run migrations
php artisan migrate

# Optional: Add test data
php artisan db:seed
```

### 3. Frontend Configuration

```bash
cd ../todo-frontend

# Install dependencies
npm install

# Verify that the .env file points to the backend API
# VITE_API_URL=http://localhost:8000/api
```

## Starting the Application

### 1. Start the Backend

```bash
cd todo-backend
php artisan serve
```

The backend server will be accessible at: http://localhost:8000

### 2. Start the Frontend

```bash
cd todo-frontend
npm run dev
```

The frontend application will be accessible at: http://localhost:5173

## Tests

### Backend Tests

```bash
cd todo-backend
php artisan test
```

## API Structure

### Authentication

- `POST /api/auth/register` - Register a new user
- `POST /api/auth/login` - User login
- `POST /api/logout` - Logout (requires authentication)

### User Profile

- `GET /api/profile` - Get the connected user's profile
- `PUT /api/profile` - Update the user's profile

### Tasks

- `GET /api/tasks` - List of user's tasks
- `POST /api/tasks` - Create a new task
- `GET /api/tasks/{id}` - Details of a specific task
- `PUT /api/tasks/{id}` - Update a task
- `DELETE /api/tasks/{id}` - Delete a task
- `PATCH /api/tasks/{id}/complete` - Mark a task as completed

### Notifications

- `GET /api/notifications` - List of notifications
- `GET /api/notifications/unread-count` - Number of unread notifications
- `POST /api/notifications/mark-all-read` - Mark all notifications as read
- `PATCH /api/notifications/{id}/read` - Mark a notification as read

## Contribution

To contribute to the project, please follow these steps:

1. Fork the project
2. Create a branch for your feature (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is under MIT license.
