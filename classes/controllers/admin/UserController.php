<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Controllers\ErrorAdminController;
    use App\Classes\Middlewares\checkingLogin;
    use App\Classes\Models\PostRepository;
    use App\Classes\Models\UserRepository;

    class UserController extends Controller {
        protected array $middlewares = [ checkingLogin::class ];

        public function displayUsers() {
            $users = UserRepository::getUsers();

            $this->render('views/templates/admin',
                'users.html.twig',
                ['users' => $users,
                'admin' => $_SESSION["admin"]]
            );
        }
    }
