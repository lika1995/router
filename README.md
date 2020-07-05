# Test Examination Project Router

## Installation:
Add this package to your composer.

composer require lika1995/router

## Usage:
```php
<?php
require '../vendor/autoload.php';
use Lika\Router\Router;

$url = $_SERVER['REQUEST_URI'];

//Set namespase for your controllers:
Router::setControllerNamespace('App\Controllers\\');
//Set default routs:
Router::addRoute('^/$', ['controller' => 'Main', 'action' => 'index']);
Router::addRoute('^/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');
//Redirects the URL to the correct route
Router::dispatch($url);

//if you need to see the current path use:
Router::getRoute();

```