# Laravel Dockerized Application

## Step 1: Clone the Repository

Clone the repository to your local machine:

```sh
git clone https://github.com/domsius/betgames.git
cd betgames
```

## Step 2: Configure Environment Variables

```sh
cp .env.example .env
```

## Step 3: Build and Start Docker Containers

```sh
docker-compose up --build -d
```

## Step 4: Install PHP Dependencies

```sh
docker exec -it laravel-app bash
composer install
```
## Step 5: Install Node Packages

```sh
npm i
npm run build
```

## Step 6: Generate Application Key

```sh
php artisan key:generate
```

## Step 7: Run Database Migrations

```sh
php artisan migrate
```

## Step 8: Access the Application

Once the setup is complete, you can access your application at http://localhost


## Design Decisions and Patterns

### Design Decisions

1.	Containerization with Docker:
    •	Using Docker to containerize the application ensures consistency across different development environments and simplifies the setup process. It helps in managing dependencies and services efficiently.
2.	Service Separation:
    •	Separate containers for the application, database, and other services (like Node.js for frontend build tasks) follow the microservices architecture principles, ensuring each component can be managed and scaled independently.
3.	Environment Configuration:
    •	Environment variables are managed through the .env file, which makes it easy to configure the application for different environments (development, staging, production).
4.	Tailwind CSS for Styling:
    •	Tailwind CSS is used for styling the application. It is a utility-first CSS framework that provides low-level utility classes to build custom designs without writing CSS. This approach speeds up development and ensures a consistent design system across the application.

### Patterns and Principles

1.	MVC (Model-View-Controller):
    •	The Laravel framework follows the MVC architectural pattern, which separates the application logic (Model), UI (View), and user input (Controller), making the codebase more modular and easier to maintain.
2.	Service Container and Dependency Injection:
    •	Laravel’s service container is used for dependency injection, making it easy to manage class dependencies and implement inversion of control. This enhances testability and reduces tight coupling between classes.
3.	Repository Pattern:
    •	The repository pattern is used to abstract the data layer, providing a clean API for data access and manipulation. This makes it easier to swap out the data source without affecting the business logic.
4.	Artisan CLI:
    •	Laravel’s built-in Artisan CLI is used for running repetitive tasks such as migrations, seeders, and tests, which improves developer productivity.
5.	Middleware:
    •	Middleware is used to filter HTTP requests entering the application. This pattern is used for tasks like authentication, logging, and CORS handling, ensuring these concerns are managed in a consistent and reusable manner.
6.	Eloquent ORM:
    •	Laravel’s Eloquent ORM is used for database interactions, which provides an easy and fluent interface for querying and manipulating data, following the Active Record pattern.