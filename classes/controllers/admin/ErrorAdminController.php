<?php
    namespace App\Classes\Controllers\Admin;

    use App\Classes\Controllers\Controller;

    class ErrorAdminController extends Controller {
        public function displayError() {
            $this->render('../views/templates/admin',
                'error_bo.html.twig',
                ['admin' => $_SESSION["admin"]]
            );
        }
    }
