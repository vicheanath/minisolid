### **MiniSolid Documentation**

---

## **Overview**

Welcome to **MiniSolid**, a lightweight PHP framework designed for simplicity, modularity, and flexibility. It supports dependency injection, middleware pipelines, and both web and API controllers with annotation-based routing.

---

## **Installation**

1. **Clone the Framework**:
   Clone the repository or download the framework files.

2. **Install Dependencies**:
   Use Composer to install required dependencies:
   ```bash
   composer install
   ```

3. **Setup Directory Structure**:
   Ensure your project has the following structure:
   ```
   /app
      /Controllers
   /public
      index.php
   /views
   /vendor
   composer.json
   ```

---

## **Getting Started**

### **1. Create Your Controllers**

#### **Web Controller**

```php
namespace App\Controllers;

use Mini\Solid\Controller;

class HomeController extends Controller {
    /**
     * @Route("/", method="GET")
     */
    public function index() {
        $this->view('home', ['title' => 'Welcome to MyFramework']);
    }

    /**
     * @Route("/about", method="GET")
     */
    public function about() {
        $this->view('about', ['title' => 'About Us']);
    }
}
```

#### **API Controller**

```php
namespace App\Controllers;

use Mini\Solid\ApiController;

class UserController extends ApiController {
    /**
     * @Route("/api/users", method="GET")
     */
    public function getUsers() {
        $users = [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ];
        $this->json($users);
    }

    /**
     * @Route("/api/users", method="POST")
     */
    public function createUser() {
        $data = $this->request->body;

        if (empty($data['name'])) {
            $this->error('Name is required', 422);
        } else {
            $this->json(['message' => 'User created', 'data' => $data], 201);
        }
    }
}
```

---

### **2. Define Your Views**

Create view files in the `/views` directory. Example:

**`views/home.php`**
```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
</head>
<body>
    <h1><?= $title ?></h1>
    <p>Welcome to your lightweight PHP framework!</p>
</body>
</html>
```

---

### **3. Configure and Run the Application**

#### **Main Entry Point (`public/index.php`)**

```php
require_once __DIR__ . '/../vendor/autoload.php';

use Mini\Solid\ApplicationBuilder;

// Create and configure the application
$app = new ApplicationBuilder();

// Register routes
$app->routes(function ($router) {
    $router->registerRoutes(App\Controllers\HomeController::class);
    $router->registerRoutes(App\Controllers\UserController::class);
});

// Run the application
$app->run();
```

#### **Start the Server**

Run PHP's built-in server:
```bash
php -S localhost:8000 -t public
```

---

### **4. Test the Application**

#### **Web Routes**
- Visit `http://localhost:8000/` → Home page
- Visit `http://localhost:8000/about` → About page

#### **API Routes**
- Visit `http://localhost:8000/api/users` (GET) → List users
- Use an API client like Postman to send a POST request to `http://localhost:8000/api/users` with JSON payload:
  ```json
  {
      "name": "John Doe"
  }
  ```

---

## **Features**

### **1. Routing**
Use the `@Route` annotation to define routes directly in your controller methods:
```php
/**
 * @Route("/path", method="HTTP_METHOD")
 */
```

### **2. Dependency Injection**
Services can be registered in the IoC container:
```php
$container->register(App\Contracts\UserRepository::class, App\Repositories\MySQLUserRepository::class, 'singleton');
```
Dependencies are resolved automatically through constructor injection.

### **3. Middleware**
Add middleware for cross-cutting concerns like logging or authentication:
```php
$app->middleware(function ($pipeline) {
    $pipeline->add(function ($request, $response, $next) {
        header('X-Powered-By: MyFramework');
        return $next($request, $response);
    });
});
```

### **4. Response Handling**
Send responses easily with helper methods:
- **View**: Render an HTML view.
  ```php
  $this->view('home', ['title' => 'Welcome']);
  ```
- **JSON**: Return JSON responses for APIs.
  ```php
  $this->json(['data' => 'value']);
  ```
- **Error**: Return standardized API errors.
  ```php
  $this->error('Something went wrong', 500);
  ```

---

## **Extending the Framework**

### **1. Adding Services**
Register new services in the IoC container:
```php
$container->register(App\Contracts\NotificationService::class, App\Services\EmailNotificationService::class);
```

### **2. Custom Middleware**
Add custom middleware to preprocess requests:
```php
$pipeline->add(function ($request, $response, $next) {
    if (!$request->headers['Authorization']) {
        return Response::json(['error' => 'Unauthorized'], 401);
    }
    return $next($request, $response);
});
```

### **3. Parameterized Routes**
Enhance the `Router` class to support dynamic route parameters like `/user/{id}`.

---

## **FAQs**

### Q: How do I handle errors globally?
You can extend the middleware pipeline to catch exceptions and return a standardized error response:
```php
$pipeline->add(function ($request, $response, $next) {
    try {
        return $next($request, $response);
    } catch (\Exception $e) {
        return Response::json(['error' => $e->getMessage()], 500);
    }
});
```

### Q: Can I use a database with this framework?
Yes, integrate any database library like PDO, Eloquent, or Doctrine. Register the database service in the IoC container.

### Q: How do I add validation?
Add a `Validator` service and use it in your controllers to validate requests before processing them.

---

## **Future Features**
- [ ] Parameterized routes (e.g., `/user/{id}`)
- [ ] Validation and form requests handling
- [ ] Middleware groups and priority
- [ ] Route caching and optimization
- [ ] Custom error pages and handling
- [ ] CSRF protection and security headers
- [ ] Session management and authentication
- [ ] Database migrations and seeding
- [ ] Improved error handling and logging
- [ ] CLI tools for scaffolding and migrations
- [ ] Support for multiple environments (dev, prod, test)
- [ ] Integration with popular libraries (Eloquent, Monolog, etc.)
- [ ] Unit and integration testing utilities
- [ ] More examples and documentation
- [ ] Community contributions and plugins
- [ ] Continuous integration and deployment
- [ ] Performance optimizations and caching

---
