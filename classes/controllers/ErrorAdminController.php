<?php
    namespace App\Classes\Controllers;

    class ErrorAdminController extends Controller {
        public function displayError() {
            $this->render('views/templates/admin',
                'error_bo.html.twig');
        }
    }
