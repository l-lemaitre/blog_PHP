<?php
    require "vendor/autoload.php";

    session_start();

    use App\Classes\Controllers\Admin\AuthController;
    use App\Classes\Controllers\Admin\CommentController;
    use App\Classes\Controllers\Admin\DashboardController;
    use App\Classes\Controllers\Admin\PostController;
    use App\Classes\Controllers\Admin\UserController;
    use App\Classes\Controllers\ErrorController;
    use App\Classes\Controllers\Public\CommentFrontController;
    use App\Classes\Controllers\Public\HomeController;
    use App\Classes\Controllers\Public\PostFrontController;
    use App\Classes\Router\Router;
    use App\Classes\Router\RouterException;

    $pathUrl = htmlspecialchars($_GET['url']);

    $pathUrl = $pathUrl == 'index' ? $pathUrl = '/' : $pathUrl;

    $pathUrl = $pathUrl == 'backoff/login' ? $pathUrl = '/backoff' : $pathUrl;

    $router = new Router($pathUrl);

    $router->get('/', function() {
        $homeController = new HomeController();
        $homeController->displayHomePage();
    });

    $router->post('/', function() {
        $homeController = new HomeController();
        $homeController->renderContactForm();
    });

    $router->get('/posts', function() {
        $postFrontController = new PostFrontController();
        $postFrontController->displayPosts();
    });

    $router->get('/post/:slug-:id', 'Public\PostFront#displayPost')->with("slug", "[a-z\-0-9]+")->with("id", "[0-9]+");

    $router->post('/post/:slug-:id', 'Public\CommentFront#renderFormAddComment')->with("slug", "[a-z\-0-9]+")->with("id", "[0-9]+");

    $router->get('/backoff/dashboard', function() {
        $dashboardController = new DashboardController();
        $dashboardController->displayDashboard();
    });

    $router->get('/backoff/posts', function() {
        $postController = new PostController();
        $postController->displayPosts();
    });

    $router->post('/backoff/posts', function() {
        $postController = new PostController();
        $postController->renderFormResetPost($_POST["resetPost"]);
    });

    $router->get('/backoff/add-post', function() {
        $postController = new PostController();
        $postController->displayAddPost();
    });

    $router->post('/backoff/add-post', function() {
        $postController = new PostController();
        $postController->renderFormAddPost();
    });

    $router->get('/backoff/post-:id', 'Admin\Post#displayEditPost')->with("id", "[0-9]+");

    if(isset($_POST["editPost"])) {
        $router->post('/backoff/post-:id', 'Admin\Post#renderFormEditPost')->with("id", "[0-9]+");
    } elseif(isset($_POST["resetPost"])) {
        $router->post('/backoff/post-:id', 'Admin\Post#renderFormResetPost')->with("id", "[0-9]+");
    }

    $router->get('/backoff/comments', function() {
        $commentController = new CommentController();
        $commentController->displayComments();
    });

    $router->post('/backoff/comments', function() {
        $commentController = new CommentController();
        $commentController->renderFormResetComment($_POST["resetComment"]);
    });

    $router->get('/backoff/comment-:id', 'Admin\Comment#displayComment')->with("id", "[0-9]+");

    if(isset($_POST["editComment"])) {
        $router->post('/backoff/comment-:id', 'Admin\Comment#renderFormEditComment')->with("id", "[0-9]+");
    } elseif(isset($_POST["resetComment"])) {
        $router->post('/backoff/comment-:id', 'Admin\Comment#renderFormResetComment')->with("id", "[0-9]+");
    }

    $router->get('/backoff/users', function() {
        $userController = new UserController();
        $userController->displayUsers();
    });

    $router->get('/backoff/logout', function() {
        $authController = new AuthController();
        $authController->logoutBackOffice();
    });

    $router->get('/backoff', function() {
        $authController = new AuthController();
        $authController->displayLoginBackOffice();
    });

    $router->post('/backoff', function() {
        $authController = new AuthController();
        $authController->renderFormLoginBackOffice();
    });

    try {
        $router->run();
    }
    catch(RouterException $e) {
        $errorController = new ErrorController();
        $errorController->displayError();
    }
