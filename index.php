<?php
    require "vendor/autoload.php";

    use App\Classes\Router\Router;
    use App\Classes\Router\RouterException;
    use App\Classes\Controllers\FrontEndController;

    $pathUrl = htmlspecialchars($_GET['url']);

    $pathUrl = $pathUrl == 'index' ? $pathUrl = '/' : $pathUrl;

    $router = new Router($pathUrl);

    $router->get('/', function() {
        $FrontEndController = new FrontEndController();
        $FrontEndController->showHomePage();
    });

    $router->get('/articles', function() {
        $FrontEndController = new FrontEndController();
        $FrontEndController->showBlogPosts();
    });

    $router->get('/article/:slug-:id/:page', 'FrontEnd#showPost')->with("slug", "[a-z\-0-9]+")->with("id", "[0-9]+")->with("page", "[0-9]+");
    $router->get('/article/:slug-:id', 'FrontEnd#showPost')->with("slug", "[a-z\-0-9]+")->with("id", "[0-9]+");

    try {
        $router->run();
    }
    catch(RouterException $e) {
        $FrontEndController = new FrontEndController();
        $FrontEndController->showError();
    }
