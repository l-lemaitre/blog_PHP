<?php
    require "vendor/autoload.php";

    use App\Classes\Router\Router;
    use App\Classes\Router\RouterException;
    use App\Classes\Controllers\FrontController;
    use App\Classes\Controllers\ErrorController;

    $pathUrl = htmlspecialchars($_GET['url']);

    $pathUrl = $pathUrl == 'index' ? $pathUrl = '/' : $pathUrl;

    $router = new Router($pathUrl);

    $router->get('/', function() {
        $FrontController = new FrontController();
        $FrontController->showHomePage();
    });

    $router->get('/articles/:page', 'Front#showBlogPosts')->with("page", "[0-9]+", function() {
        $FrontController = new FrontController();
        $FrontController->showBlogPosts();
    });

    $router->get('/articles', function() {
        $FrontController = new FrontController();
        $FrontController->showBlogPosts();
    });

    $router->get('/article/:slug-:id', 'Front#showPost')->with("slug", "[a-z\-0-9]+")->with("id", "[0-9]+");

    try {
        $router->run();
    }
    catch(RouterException $e) {
        $ErrorController = new ErrorController();
        $ErrorController->showError();
    }
