<?php
    require "vendor/autoload.php";

    use App\Classes\Router\Router;
    use App\Classes\Router\RouterException;
    use App\Classes\Controllers\FrontController;
    use App\Classes\Controllers\AdminController;
    use App\Classes\Controllers\ErrorController;

    $pathUrl = htmlspecialchars($_GET['url']);

    $pathUrl = $pathUrl == 'index' ? $pathUrl = '/' : $pathUrl;

    $pathUrl = $pathUrl == 'backoff/login' ? $pathUrl = '/backoff' : $pathUrl;

    $router = new Router($pathUrl);

    $router->get('/', function() {
        $frontController = new FrontController();
        $frontController->showHomePage();
    });

    $router->post('/', function() {
        $frontController = new FrontController();
        $frontController->renderContactForm();
    });

    $router->get('/articles/:page', 'Front#showBlogPosts')->with("page", "[0-9]+", function() {
        $frontController = new FrontController();
        $frontController->showBlogPosts();
    });

    $router->get('/articles', function() {
        $frontController = new FrontController();
        $frontController->showBlogPosts();
    });

    $router->get('/backoff', function() {
        $adminController = new AdminController();
        $adminController->showBackOffice();
    });

    $router->post('/backoff', function() {
        $adminController = new AdminController();
        $adminController->loginBackOffice();
    });

    $router->get('/article/:slug-:id', 'Front#showPost')->with("slug", "[a-z\-0-9]+")->with("id", "[0-9]+");

    try {
        $router->run();
    }
    catch(RouterException $e) {
        $errorController = new ErrorController();
        $errorController->showError();
    }
