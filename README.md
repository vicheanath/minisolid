Here's an open-source README for the project named **MiniSolid**:

---

# **MiniSolid**

![MiniSolid Logo](https://img.icons8.com/ios/452/php.png)

**MiniSolid** is a lightweight, flexible PHP framework designed for simplicity, modularity, and extensibility. It brings powerful features such as **Dependency Injection (IoC Container)**, **Middleware Pipelines**, **Routing with Annotations**, and both **Web & API Controllers** to help you build modern web applications with minimal effort. Built with **SOLID principles** in mind, it promotes clean code, separation of concerns, and scalability.

---

## **Table of Contents**

- [**MiniSolid**](#minisolid)
  - [**Table of Contents**](#table-of-contents)
  - [**Features**](#features)
  - [**Installation**](#installation)
  - [**Usage**](#usage)
  - [**Directory Structure**](#directory-structure)
  - [**Routing**](#routing)
  - [**Controllers**](#controllers)
    - [**Web Controller**:](#web-controller)
    - [**API Controller** (returns JSON responses):](#api-controller-returns-json-responses)
  - [**Responses**](#responses)
    - [**Web Response**:](#web-response)
    - [**API Response**:](#api-response)
    - [**Error Response**:](#error-response)
  - [**Middleware**](#middleware)
  - [**Extending MiniSolid**](#extending-minisolid)
    - [**1. Adding Services**](#1-adding-services)
    - [**2. Adding Custom Middleware**](#2-adding-custom-middleware)
    - [**3. Use Database**](#3-use-database)
  - [**Contributing**](#contributing)
  - [**License**](#license)
    - [**Ready to Get Started?**](#ready-to-get-started)

---

## **Features**

- **SOLID Principles**: Follows clean code principles to ensure your application is scalable and maintainable.
- **IoC Container**: Easily manage services and their dependencies with automatic dependency injection.
- **Annotation-Based Routing**: Define your routes directly in controller methods using simple annotations.
- **API & Web Controllers**: Separate logic for API responses (JSON) and traditional web views (HTML).
- **Middleware**: Add reusable middleware for tasks like logging, authentication, and request validation.
- **Fluent Builder API**: Simple, chainable API to configure your application with minimal boilerplate.

---

## **Installation**

You can install **MiniSolid** using Composer, the de facto dependency manager for PHP.

1. **Install Composer** (if you haven’t already): [Get Composer](https://getcomposer.org/download/)

2. **Install MiniSolid via Composer**:
   In your project directory, run the following command:

   ```bash
   composer require vicheanath/minisolid
   ```

   Alternatively, you can clone this repository and install the dependencies manually:

   ```bash
   git clone https://github.com/vicheanath/minisolid.git
   cd minisolid
   composer install
   ```

---

## **Usage**

1. **Create a New Project**:
   To start using **MiniSolid**, set up the following directory structure in your project:

   ```
   /app
     /Controllers
   /public
     index.php
   /views
   /vendor
   composer.json
   ```

2. **Configure the Application**:
   In `public/index.php`, you can configure the app to load controllers, routes, and middleware:

   ```php
   require_once __DIR__ . '/../vendor/autoload.php';

   use MiniSolid\ApplicationBuilder;
   use App\Controllers\HomeController;
   use App\Controllers\UserController;

   // Create and configure the application
   $app = new ApplicationBuilder();

   // Register routes and middleware
   $app->routes(function ($router) {
       $router->registerRoutes(HomeController::class);
       $router->registerRoutes(UserController::class);
   });

   // Run the application
   $app->run();
   ```

3. **Start the Server**:
   Run PHP's built-in server:

   ```bash
   php -S localhost:8000 -t public
   ```

4. **Visit in Browser**:
   - `http://localhost:8000/` → Web page
   - `http://localhost:8000/api/users` → API response (JSON)

---

## **Directory Structure**

Here is the typical directory structure for a **MiniSolid** project:

```
/app
  /Controllers
    HomeController.php
    UserController.php
/public
  index.php
/views
  home.php
  about.php
/tests
  IoCContainerTest.php
  UserControllerTest.php
/vendor
  Composer dependencies
composer.json
phpunit.xml
README.md
LICENSE
```

- **/app**: Contains all your controllers, services, and logic.
- **/public**: The entry point for your web server (usually `index.php`).
- **/views**: The directory where your views (HTML) are stored.
- **/tests**: Contains your PHPUnit tests.
- **/vendor**: Composer dependencies.

---

## **Routing**

Define routes in controller methods using **annotations** like so:

```php
namespace App\Controllers;

use MiniSolid\ControllerBase;

class HomeController extends ControllerBase {
    /**
     * @Route("/", method="GET")
     */
    public function index() {
        $this->view('home', ['title' => 'Welcome to MiniSolid']);
    }

    /**
     * @Route("/about", method="GET")
     */
    public function about() {
        $this->view('about', ['title' => 'About Us']);
    }
}
```

In your `ApplicationBuilder`, register routes as follows:

```php
$app->routes(function ($router) {
    $router->registerRoutes(HomeController::class);
});
```

---

## **Controllers**

### **Web Controller**:

```php
namespace App\Controllers;

use Mini\Solid\Controller;

class HomeController extends Controller {
    /**
     * @Route("/", method="GET")
     */
    public function index() {
        $this->view('home', ['title' => 'Welcome to MiniSolid']);
    }
}
```

### **API Controller** (returns JSON responses):

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

## **Responses**

MiniSolid handles responses for both web and API controllers:

### **Web Response**:
Render an HTML view:

```php
$this->view('home', ['title' => 'Home']);
```

### **API Response**:
Return JSON data:

```php
$this->json(['message' => 'Success']);
```

### **Error Response**:
Return error messages for APIs:

```php
$this->error('An error occurred', 500);
```

---

## **Middleware**

Add custom middleware to handle cross-cutting concerns such as logging or authentication:

```php
$app->middleware(function ($pipeline) {
    $pipeline->add(function ($request, $response, $next) {
        header('X-Powered-By: MiniSolid');
        return $next($request, $response);
    });
});
```

---

## **Extending MiniSolid**

### **1. Adding Services**

Register custom services in the IoC container:

```php
$container->register(App\Contracts\UserRepository::class, App\Repositories\MySQLUserRepository::class);
```

### **2. Adding Custom Middleware**

Add middleware for tasks like authentication:

```php
$pipeline->add(function ($request, $response, $next) {
    if (!isset($request->headers['Authorization'])) {
        return Response::json(['error' => 'Unauthorized'], 401);
    }
    return $next($request, $response);
});
```

### **3. Use Database**

To integrate a database, register a database connection service and inject it into controllers or services:

```php
$container->register(App\Contracts\DatabaseInterface::class, App\Database\MySQLConnection::class);
```

---

## **Contributing**

We welcome contributions! To get started:

1. Fork the repository.
2. Create a new feature branch.
3. Add tests for your changes.
4. Submit a pull request.

---

## **License**

MiniSolid is licensed under the [MIT License](LICENSE).

---

### **Ready to Get Started?**

- **Explore MiniSolid**: Dive into the framework and start building your next PHP application with ease!
- **Contribute**: Have an idea or improvement? Contribute to this project and help others!
