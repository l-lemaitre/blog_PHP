<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;
    use App\Classes\Middlewares\checkingLogin;

    class DashboardController extends Controller {
        protected array $middlewares = [ checkingLogin::class ];

        public function displayDashboard() {
            $this->render('views/templates/admin',
                'dashboard.html.twig',
                ['admin' => $_SESSION["admin"]]
            );
        }
    }
