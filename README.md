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
