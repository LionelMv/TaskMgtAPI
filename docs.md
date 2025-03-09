# Installing laravel

### Install Apache package

```bash
sudo apt install apache2
```

### Install MySQL

[How to install MySQL]([How To Install MySQL on Ubuntu 20.04 | DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04))

### Install php

```bash
sudo apt install php libapache2-mod-php
```

### Additional optional packages

```bash
sudo apt install php-cli
sudo apt install php-cgi
sudo apt install php-curl
sudo apt install php-xml
sudo apt install php-mysql
sudo apt install php-pgsql # For PostgreSQL
```

### Restart the Apache2 web server

```bash
sudo systemctl restart apache2.service 
sudo systemctl status apache2
```

The [guide]([How to install and configure PHP - Ubuntu Server documentation](https://documentation.ubuntu.com/server/how-to/web-services/install-php/index.html)) for reference.

### Installing Composer

- Here is the guide: [Manual]([Introduction - Composer](https://getcomposer.org/doc/00-intro.md))

- Note: Install globally.

- Go to a directory you want the composer.phar file and run this:
  
  ```bash
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  ```

- To be able to call composer globally:
  
  ```bash
  sudo mv composer.phar /usr/local/bin/composer
  ```

- Run ```composer``` to confirm it is installed.

### Install Laravel

- Install laravel installer via Composer
  
  ```bash
  composer global require laravel/installer
  ```

- Install the project
  
  Assuming you already have a repo locally. First remove files in the repo temporarily then run:
  
  ```bash
   composer create-project laravel/laravel .
  ```
  
  This option however will install the full Laravel repo via Git which includes commit history which is not what you want. Instead use:
  
  ```bash
  composer create-project --prefer-dist laravel/laravel .
  ```
  
  Provides faster installation, Lighter on Storage (no .git history), more reliable, saves bandwidth.

- If you don't have a repo and you want to start a new project, run:
  
  ```bash
   composer create-project --prefer-dist laravel/laravel <project_name>
  ```

- Kindly note that ```laravel new``` command requires you to add a PATH to .bashrc file. You can find the path using this command:
  
  ```bash
  composer global show laravel/installer
  ```

### Configure Environment Variables

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Update the database connection in `.env`:

```ini
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

For MySQL/PostgreSQL, provide respective credentials.

### Generate Application Key

```sh
php artisan key:generate
```

Open the `.env` file and make sure you see a `APP_KEY` value:

```ini
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Run Migrations & Seed Database

```sh
php artisan migrate --seed
```

### Serve the Application

```bash
php artisan serve
```

You should see a message indicating the application is running, usually at `http://127.0.0.1:8000`

Sometimes, you might get errors meaning Laravel's dependencies were not installed correctly. In such cases install Dependencies manually:

```bash
composer clear-cache
composer install
```

## Install and Configure Laravel Sanctum

Laravel Sanctum is a simple authentication system for API token authentication.

- Install Sanctum
  
  ```bash
  composer require laravel/sanctum
  ```

- Pulblish Sanctum Configuration:
  
  ```bash
  php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
  ```

- This will create a config file at: ```config/sanctum.php```.

- Run migrations
  
  ```bash
  php artisan migrate
  ```

- Add Sanctum Middleware. This is added in ```app/Http/Kernel.php```:
  
  ```php
  protected $middlewareGroups = [
      ...
      'api' => [
          \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
          'throttle:api',
          \Illuminate\Routing\Middleware\SubstituteBindings::class,
      ],
  ];
  ```
  
  - Sometimes the Kernel.php file is missing and will require you to create it manually if ```composer install``` does not regenerate it. Check the default code removing the Sanctum Middleware from this project.

- Configure Sanctum in User Model. Add this trait in ```app/Models/User.php```:
  
  ```php
  use Laravel\Sanctum\HasApiTokens;
  
  class User extends Authenticatable
  {
      use HasApiTokens, HasFactory, Notifiable;
  }
  ```

- Create Authentication Routes in ```routes/api.php```:
  
  - This is for ```/register```, ```/login```,``` /logout```.

- Modify ```bootstrap/app.php``` to include ```api.php```:
  
  ```php
  ->withRouting(
      web: __DIR__.'/../routes/web.php',
      api: __DIR__.'/../routes/api.php', // ✅ Add this line
      commands: __DIR__.'/../routes/console.php',
      health: '/up',
  )
  ```

- Check if the API routes are available:
  
  ```bash
  php artisan route:list
  ```

- Sometimes you might make changes and they do not reflect. You need to clear the route cache and restart Laravel.
  
  ```bash
  php artisan route:clear
  php artisan cache:clear
  php artisan config:clear
  php artisan serve
  ```

- Create AuthController:
  
  ```bash
  php artisan make:controller AuthController
  ```
  
  - Open ```app/Http/Controllers/AuthController.php``` and create the controller for register, login and logout.

- Test Authentication endpoints. You can use Postman or curl.
  
  - curl example:
    
    ```bash
    curl -X POST http://127.0.0.1:8000/api/register \
         -H "Content-Type: application/json" \
         -d '{
               "name": "John Doe",
               "email": "john@example.com",
               "password": "password123"
             }'
    ```
  
  - or:
    
    ```bash
    curl -X POST http://127.0.0.1:8000/api/logout \
         -H "Authorization: Bearer YOUR_TOKEN_HERE"
    ```



## CRUD Operations

- Create Task Model and Migration
  
  ```bash
  database/migrations/YYYY_MM_DD_create_tasks_table.php
  ```

- This will create a model: ```app/Models/Task.php``` and a migration file: ```database/migrations/YYYY_MM_DD_create_tasks_table.php```

- Open the migration file in **`database/migrations`** and update the `up()` method.

- Apply the migrations to create the table:
  
  ```bash
  php artisan migrate
  ```

- Set Up Relationships in ```app/Models/Task.php```.

- Add this method to the User model in `app/Models/User.php`
  
  ```php
  public function tasks(): HasMany
  {
      return $this->hasMany(Task::class);
  }
  ```

- Create TaskController and implement the methods index (GET), store (POST), update (PUT) and destroy (DELETE) for the tasks.
  
  ```bash
  php artisan make:controller TaskController
  ```

- Define CRUD Routes in ```routes/api.php```.

- Create the policy to prevent users from accessing others' tasks. Modify the ```app/Policies/TaskPolicy.php``` file to prevent others from updating or deleting other users tasks.
  
  ```bash
  php artisan make:policy TaskPolicy --model=Task
  ```

- Create the ```AuthServiceProvider.php``` file:
  
  ```bash
  php artisan make:provider AuthServiceProvider
  ```

- Register the TaskPolicy in the AuthServiceProvider file:
  
  ```php
  protected $policies = [
      Task::class => TaskPolicy::class,
  ];
  ```

- Register the provider in ```bootstrap/providers.php```
  
  ```php
  ->withProviders([
      App\Providers\AppServiceProvider::class,
      App\Providers\AuthServiceProvider::class,
  ])
  ```

- Apply Middleware Authorization in Routes (```routes/api.php```) for update and delete especially.

- Clear cache
  
  ```bash
  php artisan optimize:clear
  ```

- Test the endpoints work in Postman or using curl. Also try deleting or updating someone else's task.



## Filtering Tasks

Need to allow users to filter tasks based on their status (pending or completed).

- Modify index() method in ```TaskContoller.php``` and add:
  
  ```php
  if ($request->has('status')) {
      $query->where('status', $request->status);
  }
  ```

- Filter tasks by status via the API:
  
  ```bash
  curl -X GET "http://127.0.0.1:8000/api/tasks?status=pending" -H "Authorization: Bearer YOUR_TOKEN_HERE"
  ```



## Add Pagination

- Modify index() method in `TaskContoller.php` and add:
  
  ```php
  // Paginate results (default 10 per page, can be adjusted via ?per_page=5)
  $tasks = $query->paginate($request->input('per_page', 10));
  ```

- Test Pagination via API:
  
  - Get the first page of tasks (default is 10 per page):
    
    ```bash
    curl -X GET "http://127.0.0.1:8000/api/tasks" -H "Authorization: Bearer YOUR_TOKEN_HERE"
    ```
  
  - Get the second page of tasks:
    
    ```bash
    curl -X GET "http://127.0.0.1:8000/api/tasks?page=2" -H "Authorization: Bearer YOUR_TOKEN_HERE"
    ```
  
  - Get 5 tasks per page:
    
    ```bash
    curl -X GET "http://127.0.0.1:8000/api/tasks?per_page=5" -H "Authorization: Bearer YOUR_TOKEN_HERE"
    ```
