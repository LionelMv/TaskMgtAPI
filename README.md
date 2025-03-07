# Task Management API

## Introduction
This is a simple Task Management API built using Laravel 12. It allows users to register, log in, create, update, delete, and retrieve tasks. Each user can only manage their own tasks.

## Requirements
- PHP 8.2+
- Composer
- Laravel 12
- SQLite/MySQL/PostgreSQL (configured in `.env` file)
- Postman or cURL for testing

## Installation

### Step 1: Clone the Repository
```sh
git clone <repository-url>
cd TaskMgtAPI
```

### Step 2: Install Dependencies
```sh
composer install
```

### Step 3: Configure Environment Variables
Copy the `.env.example` file to `.env`:
```sh
cp .env.example .env
```
Update the database connection in `.env`:
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```
For MySQL/PostgreSQL, provide respective credentials.

### Step 4: Generate Application Key
```sh
php artisan key:generate
```

### Step 5: Run Migrations & Seed Database
```sh
php artisan migrate --seed
```

### Step 6: Start the Application
```sh
php artisan serve
```

## API Endpoints

### Authentication

#### Register
**Endpoint:** `POST /api/register`
**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

#### Login
**Endpoint:** `POST /api/login`
**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password"
}
```

#### Logout
**Endpoint:** `POST /api/logout`
**Headers:**
```
Authorization: Bearer {token}
```

### Tasks

#### Create Task
**Endpoint:** `POST /api/tasks`
**Headers:**
```
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "title": "Buy groceries",
  "description": "Milk, Bread, Eggs",
  "status": "pending"
}
```

#### Get All Tasks (with Pagination & Filtering)
**Endpoint:** `GET /api/tasks?status=pending&page=1`
**Headers:**
```
Authorization: Bearer {token}
```

#### Update Task
**Endpoint:** `PUT /api/tasks/{task}`
**Headers:**
```
Authorization: Bearer {token}
```
**Request Body:**
```json
{
  "title": "Buy vegetables",
  "description": "Carrots, Tomatoes, Potatoes",
  "status": "completed"
}
```

#### Delete Task
**Endpoint:** `DELETE /api/tasks/{task}`
**Headers:**
```
Authorization: Bearer {token}
```

## Postman Collection
Import the provided Postman collection to test the API.

## License
This project is open-source under the MIT License.

